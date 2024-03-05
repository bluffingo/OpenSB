<?php

namespace OpenSB;

global $orange;

use SquareBracket\Pages\AccountSettings;
use SquareBracket\Templating;

$page = new AccountSettings($orange);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);

echo $twig->render('settings.twig');
