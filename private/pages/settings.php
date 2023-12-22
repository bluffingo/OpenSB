<?php

namespace Orange;

global $orange;

use Orange\Templating;

require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/AccountSettings.php';

$page = new \Orange\Pages\AccountSettings($orange);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);

echo $twig->render('settings.twig');
