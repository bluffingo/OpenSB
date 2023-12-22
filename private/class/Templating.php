<?php

namespace Orange;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\String\StringExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * A rewrite of openSB's /private/layout.php.
 *
 * @since Orange 1.0
 */
class Templating
{
    private $skin;
    private FilesystemLoader $loader;
    private Environment $twig;

    public function __construct(\Orange\Orange $betty)
    {
        global $isQoboTV, $auth, $bettyTemplate, $isDebug, $branding, $googleAdsClient;
        chdir(__DIR__ . '/..');
        $this->skin = $betty->getLocalOptions()["skin"] ?? $bettyTemplate;

        if ($this->skin === null || trim($this->skin) === '' || !is_dir('skins/' . $this->skin . '/templates')) {
            trigger_error("Currently selected skin is invalid", E_USER_WARNING);
            $this->skin = $bettyTemplate;
        }

        $this->loader = new FilesystemLoader('skins/' . $this->skin . '/templates');
        $this->loader->addPath('skins/common/');
        $this->twig = new Environment($this->loader, ['debug' => $isDebug]);

        $this->twig->addExtension(new OrangeTwigExtension());
        $this->twig->addExtension(new StringExtension());

        if ($isDebug) {
            $this->twig->addExtension(new DebugExtension());
        } else {
            $this->twig->addFunction(new TwigFunction('dump', function() {
                return "This function is not available outside of debug mode.";
            }));
        }


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

        $this->twig->addGlobal('is_qobo', $isQoboTV);
        $this->twig->addGlobal('is_debug', $isDebug);
        $this->twig->addGlobal('is_user_logged_in', $auth->isUserLoggedIn());
        $this->twig->addGlobal('user_data', $auth->getUserData());
        $this->twig->addGlobal('user_ban_data', $auth->getUserBanData());
        $this->twig->addGlobal('user_notice_data', $auth->getUserNoticesCount());
        $this->twig->addGlobal('skins', $this->getAllSkinsMetadata());
        $this->twig->addGlobal('squarebracket_version', $betty->getVersionString());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('website_branding', $branding);
        $this->twig->addGlobal('ad_client', $googleAdsClient);
        $this->twig->addGlobal('show_work_in_progress_stuff', ($betty->getLocalOptions()["development"] ?? false));

        $this->twig->addGlobal("page_url", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $this->twig->addGlobal("domain", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
    }

    /**
     * Get all the available skins.
     *
     * @since Orange 1.0
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
     * @since Orange 1.0
     *
     * @param $skin
     * @return array|null
     */
    public function getSkinMetadata($skin): ?array
    {
        if (file_exists($skin . "/skin.json")) {
            $metadata = file_get_contents($skin . "/skin.json");
        } else {
            trigger_error(sprintf("The metadata for Betty skin %s is missing", $skin), E_USER_WARNING);
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
     * This function exists to keep compatibility with openSB pages based on twigloader.
     *
     * @param $template
     * @param array $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @since Orange 1.0
     *
     */
    public function render($template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}

