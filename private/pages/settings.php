<?php

namespace OpenSB;

global $twig, $auth, $database;

use SquareBracket\Pages\AccountSettings;
use SquareBracket\UnorganizedFunctions;

global $auth;

if (!$auth->isUserLoggedIn())
{
    UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
}

// we shouldn't let banned users change settings.
/*
if ($auth->getUserBanData()) {
    $orange->Notification("You cannot proceed with this action.", "/");
}
*/

if (isset($_POST['save'])) {
    global $auth, $storage;

    $title = htmlspecialchars($_POST['title']) ?? null;
    $about = $_POST['about'] ?? null;

    $resetToken = $_POST['reset_token'] ?? null;

    $currentPass = ($_POST['current_pass'] ?? null);
    $pass = ($_POST['pass'] ?? null);
    $pass2 = ($_POST['pass2'] ?? null);

    $rating = isset($_POST['rating']) && $_POST['rating'] === 'true' ? 'mature' : 'general';

    $error = '';

    if ($currentPass && $pass && $pass2) {
        $password = $database->fetch("SELECT password FROM users WHERE id = ?", [$auth->getUserID()])["password"];
        if (password_verify($currentPass, $password)) {
            if ($pass == $pass2) {
                $database->query("UPDATE users SET password = ?, token = ? WHERE id = ?",
                    [password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $auth->getUserID()]);

                // TODO
                die("TODO: REDIRECT TO LOGIN PAGE");
                //redirect('login.php?new_pass');
            } else {
                $error .= " The new passwords aren't identical.";
            }
        } else {
            $error .= "Your current password is incorrect.";
        }
    }
    if ($error) $error = "The following errors occured while changing your password: " . $error;

    // does this even work???
    if ($resetToken) {
        $database->query("UPDATE users SET token = ? WHERE id = ?", [bin2hex(random_bytes(32)), $auth->getUserID()]);
        // TODO
        die("TODO: REDIRECT TO LOGIN PAGE");
        //redirect('login.php?new_pass');
    }

    // banned users shouldn't be able to change their profile
    if (strlen($title) > 50) {
        $error .= "Your display name is too long.";
    }
    if (!empty($_FILES['profilePicture']['name'])) {
        $name = $_FILES['profilePicture']['name'];
        $temp_name = $_FILES['profilePicture']['tmp_name'];
        $ext = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
        $storage->uploadProfilePicture($temp_name, $auth->getUserData()["name"]);
    }

    if (!$error) {
        $database->query("UPDATE users SET title = ?, about = ?, comfortable_rating = ? WHERE id = ?",
            [$title, $about, $rating, $auth->getUserID()]);
        UnorganizedFunctions::Notification("Edited successfully!", ("user.php?name=" . $auth->getUserData()["name"]), "success");
    } else {
        UnorganizedFunctions::Notification($error, "/settings.php");
    }
}

echo $twig->render('settings.twig');
