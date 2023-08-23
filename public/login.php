<?php

namespace openSB;

global $betty;
require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/AccountLogin.php';

$error = '';

$page = new \Betty\Pages\AccountLogin($betty);

if (isset($_POST["loginsubmit"])) {
    $page->postData($_POST);
    $username = ($_POST['username'] ?? null);
    $password = ($_POST['password'] ?? null);

    // Check to see if the user actually has entered anything.
    if (!$username) $error = __("Please enter your username! ");
    if (!$password) $error = __("Please enter your password! ");

    if (empty($error)) {
        $logindata = $sql->fetch("SELECT password,token FROM users WHERE name = ?", [$username]);
        if ($logindata && password_verify($password, $logindata['password'])) {
            setcookie('SBTOKEN', $logindata['token'], 2147483647);
            $nid = $sql->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
            $sql->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), getUserIpAddr(), $nid]);

            if ($googleAPI) {
                $loginEventData = new LoginEvent();
                $loginEventData->setMethod('BettySB');
                $baseRequest->addEvent($loginEventData);
                $ga->send($baseRequest);
            }

            redirect('./');
        } else {
            $error = __("Incorrect username or password.");
        }
    }
}

$twig = new \Betty\Templating($betty);

echo $twig->render('login.twig', [
    'error' => $error,
    'resetted' => isset($_GET['resetted']),
    'new_pass' => isset($_GET['new_pass']),
    'new_token' => isset($_GET['new_token']),
]);
