<?php

namespace SquareBracket;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * A rewrite of openSB's /private/layout.php.
 *
 * @since SquareBracket 1.0
 */
class Templating
{
    private $skin;
    private $theme;
    private FilesystemLoader $loader;
    private Environment $twig;

    /**
     * @throws LoaderError
     */
    public function __construct(SquareBracket $orange)
    {
        global $isChazizSB, $auth, $defaultTemplate, $isDebug, $branding, $enableInviteKeys;
        chdir(__DIR__ . '/../..');
        $this->skin = $orange->getLocalOptions()["skin"] ?? $defaultTemplate;
        $this->theme = $orange->getLocalOptions()["theme"] ?? "default";

        // biscuit frontend is now internally called "biscuit" to avoid any confusion with bitqobo
        if ($this->skin == "qobo")
        {
            $this->skin = "biscuit";
        }

        // TODO: reset theme if the user changes their skin to another skin
        if ($this->skin === null || trim($this->skin) === '' || !is_dir('skins/' . $this->skin . '/templates')) {
            trigger_error("Currently selected skin is invalid", E_USER_WARNING);
            $this->skin = $defaultTemplate;
        }

        $loader_path = 'skins/' . $this->skin . '/templates';
        $this->loader = new FilesystemLoader($loader_path);
        $this->loader->addPath('skins/common/');
        $this->twig = new Environment($this->loader, ['debug' => $isDebug]);

        // gets relevant path for include to use
        $this->twig->addFunction(new TwigFunction('component', function($component) use ($loader_path) {
            $path = '/components/' . $this->theme . '/' . $component . '.twig';
            $path_default = '/components/default/' . $component . '.twig';

            if (file_exists(SB_PRIVATE_PATH . '/' . $loader_path . $path)) {
                return $path;
            } elseif (file_exists(SB_PRIVATE_PATH . '/' . $loader_path . $path_default)) {
                return $path_default;
            } else {
                return '/missing_component.twig';
            }
        }));

        $this->twig->addExtension(new SquareBracketTwigExtension());
        $this->twig->addExtension(new StringExtension());

        // 2021 SQUAREBRACKET FRONTEND COMPATIBILITY
        $this->twig->addFunction(new TwigFunction('__', function($string, $placeholders = []) {
            return vsprintf($string, $placeholders);
        }));

        $this->twig->addFunction(new TwigFunction('small_video_box', function() {
            return false;
        }));

        $this->twig->addFunction(new TwigFunction('video_box', function() {
            return false;
        }));

        $this->twig->addFunction(new TwigFunction('browse_video_box', function() {
            return false;
        }));

        $this->twig->addFunction(new TwigFunction('icon', function($icon, $size) {
            return $this->render('components/icon.twig', ['icon' => $icon, 'size' => $size]);
        }, ['is_safe' => ['html']]));
        // ---------------------------

        if ($isDebug) {
            $this->twig->addExtension(new DebugExtension());
        } else {
            $this->twig->addFunction(new TwigFunction('dump', function() {
                return "This function is not available outside of debug mode.";
            }));
        }

        $this->twig->addGlobal('is_chaziz_sb', $isChazizSB);
        $this->twig->addGlobal('is_debug', $isDebug);
        $this->twig->addGlobal('is_user_logged_in', $auth->isUserLoggedIn());
        $this->twig->addGlobal('user_data', $auth->getUserData());
        $this->twig->addGlobal('user_ban_data', $auth->getUserBanData());
        $this->twig->addGlobal('user_notice_data', $auth->getUserNoticesCount());
        $this->twig->addGlobal('skins', $this->getAllSkinsMetadata());
        $this->twig->addGlobal('opensb_version', (new VersionNumber)->getVersionString());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('website_branding', $branding);
        $this->twig->addGlobal('current_theme', $this->theme); // not to be confused with skins
        $this->twig->addGlobal('invite_keys_enabled', $enableInviteKeys);

        if (isset($_SERVER["REQUEST_URI"])) {
            $this->twig->addGlobal('page_name', empty(basename($_SERVER["REQUEST_URI"], '.php')) ? 'index' : basename($_SERVER["REQUEST_URI"], '.php'));
        }

        if (isset($_SERVER['HTTP_HOST'])) {
            $this->twig->addGlobal("page_url", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $this->twig->addGlobal("domain", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
        }
    }

    /**
     * Get all the available skins.
     *
     * @since SquareBracket 1.0
     *
     * @return string[]
     */
    public function getAllSkins(): array
    {
        $skins = [];
        $unfiltered_skins = glob('skins/*', GLOB_ONLYDIR);

        foreach($unfiltered_skins as $skin) {
            if ($skin != "skins/common") {
                $skins[] = $skin;
            }
        }

        return $skins;
    }

    /**
     * Get the skin's JSON metadata.
     *
     * @since SquareBracket 1.0
     *
     * @param $skin
     * @return array|null
     */
    public function getSkinMetadata($skin): ?array
    {
        if (file_exists($skin . "/skin.json")) {
            $metadata = file_get_contents($skin . "/skin.json");
        } else {
            trigger_error(sprintf("The metadata for OpenSB skin %s is missing", $skin), E_USER_WARNING);
            return null;
        }
        return json_decode($metadata, true);
    }

    public function getAllSkinsMetadata(): array
    {
        $skins = [];
        foreach($this->getAllSkins() as $skin) {
            $skins[] = $this->getSkinMetadata($skin);
        }
        return $skins;
    }

    /**
     *
     * @param $template
     * @param array $data
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @since SquareBracket 1.0
     *
     */
    public function render($template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}

