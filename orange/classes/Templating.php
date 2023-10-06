<?php

namespace Orange;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * A rewrite of openSB's /private/layout.php.
 *
 * @since 0.1.0
 */
class Templating
{
    private $skin;
    private FilesystemLoader $loader;

    public function __construct(\Orange\Orange $betty)
    {
        global $isQoboTV, $auth, $bettyTemplate, $isDebug, $branding, $googleAdsClient;
        chdir(__DIR__ . '/..');
        $this->skin = $betty->getLocalOptions()["skin"] ?? $bettyTemplate;
        $this->loader = new FilesystemLoader('skins/' . $this->skin . '/templates');
        $this->loader->addPath('skins/common/');
        $this->twig = new Environment($this->loader, ['debug' => $isDebug]);

        $this->twig->addExtension(new BettyTwigExtension());

        if ($isDebug) {
            $this->twig->addExtension(new DebugExtension());
        } else {
            $this->twig->addFunction(new TwigFunction('dump', function() {
                return "This instance is not in debug mode.";
            }));
        }

        $this->twig->addGlobal('is_qobo', $isQoboTV);
        $this->twig->addGlobal('is_debug', $isDebug);
        $this->twig->addGlobal('is_user_logged_in', $auth->isUserLoggedIn());
        $this->twig->addGlobal('user_data', $auth->getUserData());
        $this->twig->addGlobal('user_ban_data', $auth->getUserBanData());
        $this->twig->addGlobal('user_notice_data', $auth->getUserNoticesCount());
        $this->twig->addGlobal('skins', $this->getAllSkinsMetadata());
        $this->twig->addGlobal('squarebracket_version', $betty->getBettyVersion());
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('website_branding', $branding);
        $this->twig->addGlobal('ad_client', $googleAdsClient);
        $this->twig->addGlobal('show_work_in_progress_stuff', ($betty->getLocalOptions()["development"] ?? false));

        $this->twig->addGlobal("page_url", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $this->twig->addGlobal("domain", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
    }

    /**
     * Get all the available skins. Currently, hardcoded to only Finalium.
     *
     * @since 0.1.0
     *
     * @return string[]
     */
    public function getAllSkins(): array
    {
        return [
            "qobo" => "skins/qobo/",
        ];
    }

    /**
     * Get the skin's JSON metadata.
     *
     * @since 0.1.0
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
     * @since 0.1.0
     *
     */
    public function render($template, array $data = []): string
    {
        return $this->twig->render($template, $data);
    }
}

