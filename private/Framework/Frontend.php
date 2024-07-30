<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use OpenSB\App;
use OpenSB\Framework\FrontendTwigExtension;
use OpenSB\Framework\DB;
use OpenSB\Framework\Auth;

class Frontend {
    private $twig;
    private $db;
    private $auth;

    function __construct() {
        $loader = new \Twig\Loader\FilesystemLoader($_SERVER["DOCUMENT_ROOT"] . '/../src/templates/prototype/');

        $this->twig = new \Twig\Environment($loader, [
            //"cache" => $_SERVER['DOCUMENT_ROOT'] . '/../.twigcache',
        ]);
        $this->twig->addExtension(new FrontendTwigExtension());

        $this->db = App::container()->get(DB::class);
        $this->auth = App::container()->get(Auth::class);

        $this->twig->addGlobal('loggedIn', $this->auth->isLoggedIn());
        $this->twig->addGlobal('userData', $this->auth->getUserData());
    }

    function render($name, $array = []) {
        $template = $this->twig->load($name . '.twig');
        echo $template->render($array);
    }
}
