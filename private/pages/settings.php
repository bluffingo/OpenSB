<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\AccountSettings;

$page = new AccountSettings($orange);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

echo $twig->render('settings.twig');
