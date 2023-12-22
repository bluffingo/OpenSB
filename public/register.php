<?php

namespace openSB;

global $gump, $orange;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/AccountRegister.php';

$page = new \Orange\Pages\AccountRegister($orange);

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $page->postData($_POST);
}

$twig = new \Orange\Templating($orange);
echo $twig->render('register.twig');
