<?php

namespace OpenSB;

global $gump, $orange;

use SquareBracket\Pages\AccountRegister;
use SquareBracket\Templating;

$page = new AccountRegister($orange);

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);
echo $twig->render('register.twig');
