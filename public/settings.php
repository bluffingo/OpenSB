<?php

namespace openSB;

global $betty;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/AccountSettings.php';

$page = new \Orange\Pages\AccountSettings($betty);

if (isset($_POST['save'])) {
    $page->postData($_POST);
}

$twig = new Templating($betty);

echo $twig->render('settings.twig');
