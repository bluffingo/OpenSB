<?php

namespace OpenSB;

global $twig, $database;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\Utilities;

if (isset($_POST["loginsubmit"])) {
    $error = false;

    $username = ($_POST['username'] ?? null);
    $password = ($_POST['password'] ?? null);
    $rememberMe = isset($_POST['remember_me']);

    if (!$username) $error = true;
    if (!$password) $error = true;

    if (!$error) {
        $logindata = $database->fetch("SELECT password,token,ip FROM users WHERE name = ?", [$username]);

        if ($logindata && password_verify($password, $logindata['password'])) {
            // check if the account is from an ip that is in ipbans
            $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [$logindata['ip']]);

            if ($ipban) {
                UnorganizedFunctions::Notification("This account's latest IP address is banned.", "/login.php");
            }

            if ($rememberMe) {
                $expires = time() + (365 * 24 * 60 * 60);
            } else {
                $expires = time() + (30 * 24 * 60 * 60);
            }

            setcookie('SBTOKEN', $logindata['token'], [
                'expires' => $expires,
                'path' => '/',
                'domain' => '',
                'secure' => false,
                'httponly' => true,
                'samesite' =>'Lax',
            ]);

            $nid = $database->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
            $database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), Utilities::get_ip_address(), $nid]);

            UnorganizedFunctions::redirect('./');
        } else {
            UnorganizedFunctions::Notification("Incorrect credentials.", "/login.php");
        }
    } else {
        UnorganizedFunctions::Notification("Please input your credentials.", "/login.php");
    }
}

echo $twig->render('login.twig');
