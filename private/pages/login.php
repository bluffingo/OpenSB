<?php

namespace Orange;

global $orange;
require_once dirname(__DIR__) . '/class/common.php';

require_once dirname(__DIR__) . '/class/Pages/AccountLogin.php';

$error = '';

$page = new \Orange\Pages\AccountLogin($orange);

if (isset($_POST["loginsubmit"])) {
    $page->postData($_POST);
}

$twig = new \Orange\Templating($orange);

echo $twig->render('login.twig', [
    'error' => $error,
    'resetted' => isset($_GET['resetted']),
    'new_pass' => isset($_GET['new_pass']),
    'new_token' => isset($_GET['new_token']),
]);
