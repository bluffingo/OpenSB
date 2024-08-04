<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use OpenSB\App;
use OpenSB\Framework\FrontendTwigExtension;
use OpenSB\Framework\Database;
use OpenSB\Framework\Authentication;

class Frontend {
    private $twig;
    private $db;
    private $auth;
    private $site;
    private $feature_flags;

    function __construct() {
        $this->site = App::config()["site"] ?? "squarebracket";

        // hardcoded to default biscuit for now since theme customization page hasnt been ported yet
        if ($this->site == "soos") {
            $skin = "soos";
        } else {
            $skin = "biscuit";
        }
        $theme = "default";

        $loader = new FilesystemLoader($_SERVER["DOCUMENT_ROOT"] . "/../private/skins/$skin/templates/");

        $this->twig = new Environment($loader, [
            //"cache" => $_SERVER['DOCUMENT_ROOT'] . '/../.twigcache', TODO
        ]);
        $this->twig->addExtension(new FrontendTwigExtension());

        $this->db = App::container()->get(Database::class);
        $this->auth = App::container()->get(Authentication::class);
        $this->feature_flags = App::container()->get(SiteConfig::class)->getFeatureFlags();

        $this->twig->addGlobal('is_user_logged_in', $this->auth->isLoggedIn());
        $this->twig->addGlobal('user_data', $this->auth->getUserData());
        $this->twig->addGlobal('current_theme', $theme);
        $this->twig->addGlobal('feature_flags', $this->feature_flags);

        // temporary measure to update the frontend code without breaking the old orange backend until we toss
        // that shit out
        $this->twig->addGlobal('areWeRunningTheNewCode', true);
    }

    function render($name, $array = []) {
        $template = $this->twig->load($name . '.twig');
        echo $template->render($array);
    }
}
