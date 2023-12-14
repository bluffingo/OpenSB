<?php

namespace openSB;

global $gump, $betty;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/AccountRegister.php';

$page = new \Orange\Pages\AccountRegister($betty);

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $page->postData($_POST);
}

$twig = new \Orange\Templating($betty);
echo $twig->render('register.twig');
