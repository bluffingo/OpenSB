<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use OpenSB\App;
use OpenSB\Framework\FrontendTwigExtension;
use OpenSB\Framework\DB;
use OpenSB\Framework\Auth;
use Twig\Loader\FilesystemLoader;

class Frontend {
    private $twig;
    private $db;
    private $auth;

    function __construct() {
        // hardcoded to default biscuit for now
        $loader = new FilesystemLoader($_SERVER["DOCUMENT_ROOT"] . '/../private/skins/biscuit/templates/');

        $this->twig = new \Twig\Environment($loader, [
            //"cache" => $_SERVER['DOCUMENT_ROOT'] . '/../.twigcache',
        ]);
        $this->twig->addExtension(new FrontendTwigExtension());

        $this->db = App::container()->get(DB::class);
        $this->auth = App::container()->get(Auth::class);

        $this->twig->addGlobal('is_user_logged_in', $this->auth->isLoggedIn());
        $this->twig->addGlobal('user_data', $this->auth->getUserData());
        $this->twig->addGlobal('current_theme', "default");

        // temporary measure to update the frontend code without breaking old backend until we toss that shit out
        $this->twig->addGlobal('areWeRunningTheNewCode', true);
    }

    function render($name, $array = []) {
        $template = $this->twig->load($name . '.twig');
        echo $template->render($array);
    }
}
