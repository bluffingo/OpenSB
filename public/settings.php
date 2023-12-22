<?php

namespace openSB;

global $orange;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/AccountSettings.php';

$page = new \Orange\Pages\AccountSettings($orange);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

$twig = new Templating($orange);

echo $twig->render('settings.twig');
