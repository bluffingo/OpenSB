<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$error = '';

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {
    $username = (isset($_POST['username']) ? $_POST['username'] : null);
    $pass = (isset($_POST['pass1']) ? $_POST['pass1'] : null);
    $pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
    $mail = (isset($_POST['email']) ? $_POST['email'] : null);

    if (!isset($username)) $error .= __("Blank username.");
    if (!isset($pass) || strlen($pass) < 8) $error .= __("Password is too short. ");
    if (!isset($pass2) || $pass != $pass2) $error .= __("The passwords don't match. ");
    if ($sql->result("SELECT COUNT(*) FROM users WHERE name = ?", [$username])) $error .= __("Username has already been taken. "); //ashley2012 bypassed this -gr 7/26/2021
    if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $username)) $error .= __("Username contains invalid characters (Only alphanumeric and underscore allowed). "); //ashley2012 bypassed this with the long-ass arabic character. -gr 7/26/2021
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $error .= "Email isn't valid. ";
    if ($sql->result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail])) $error .= "You've already registered an account using this email address. ";
    if ($sql->result("SELECT COUNT(*) FROM users WHERE ip = ?", [getUserIpAddr()]) > 10)
        $error .= "Creating more than 10 accounts isn't allowed. ";

    if ($error == '') {
        $token = bin2hex(random_bytes(32));
        $sql->query("INSERT INTO users (name, password, token, joined, title, email) VALUES (?,?,?,?,?,?)",
            [$username, password_hash($pass, PASSWORD_DEFAULT), $token, time(), $username, mailHash($mail)]);

        $newUser = $sql->result("SELECT `id` from `users` where `name` = ?", [$username]);

        setcookie('SBTOKEN', $token, 2147483647);

        redirect('./');
    }
}

$twig = twigloader();
echo $twig->render('register.twig', ['error' => $error]);
