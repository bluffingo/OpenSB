<?php

namespace OpenSB;

global $orange;

use Orange\Templating;
use Orange\Pages\AccountSettings;

$page = new AccountSettings($orange);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);

echo $twig->render('settings.twig');
