<?php

namespace OpenSB;

global $gump, $orange;

use Orange\Templating;
use Orange\Pages\AccountRegister;

$page = new AccountRegister($orange);

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);
echo $twig->render('register.twig');
