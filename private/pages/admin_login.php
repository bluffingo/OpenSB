<?php

namespace OpenSB;

global $auth, $twig, $database, $orange, $path;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page.", "/");
}

if ($orange->getLocalOptions()["skin"] != "biscuit" && $orange->getLocalOptions()["skin"] != "charla") {
    UnorganizedFunctions::Notification("Please change your skin to Biscuit.", "/theme");
}

if (isset($_POST["loginsubmit"])) {
    $error = false;

    $username = ($_POST['username'] ?? null);
    $password = ($_POST['password'] ?? null);

    if (!$username) $error = true;
    if (!$password) $error = true;

    if ($username != $auth->getUserData()["name"]) {
        UnorganizedFunctions::Notification("You must log into the admin panel with your current username.",
            "/admin/login");
    }

    if (!$error) {
        $logindata = $database->fetch("SELECT admin_password FROM users WHERE name = ?", [$username]);

        if ($logindata && password_verify($password, $logindata['admin_password'])) {
            $_SESSION["SB_ADMIN_AUTHED"] = true;
            UnorganizedFunctions::Notification("Welcome!", "/admin/", "success");
        } else {
            UnorganizedFunctions::Notification("Incorrect admin password.", "/admin/login");
        }
    }
}

echo $twig->render('admin_login.twig');