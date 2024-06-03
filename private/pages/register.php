<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\AccountRegister;
use SquareBracket\Templating;

$page = new AccountRegister($orange);

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $page->postData($_POST);
}

echo $twig->render('register.twig');
