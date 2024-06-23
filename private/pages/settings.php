<?php

namespace OpenSB;

global $twig, $auth, $database;

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

    $currentPass = ($_POST['current_pass'] ?? null);
    $pass = ($_POST['pass'] ?? null);
    $pass2 = ($_POST['pass2'] ?? null);
    $new_username = $_POST['new_username'] ?? null;

    $customcolor = ($_POST['customcolor'] ?? '#523bb8');

    $rating = isset($_POST['rating']) && $_POST['rating'] === 'true' ? 'mature' : 'general';
    $blacklisted_tags = ($_POST['blacklisted_tags'] ?? $auth->getDefaultBlacklistedTags());

    if ($blacklisted_tags === '') {
        $parsed_tags = [];
    } else {
        $parsed_tags = preg_split('/[\s,]+/', trim($blacklisted_tags, ","));
    }

    $error = '';

    $password = $database->fetch("SELECT password FROM users WHERE id = ?", [$auth->getUserID()])["password"];
    if ($currentPass && $pass && $pass2) {
        if (password_verify($currentPass, $password)) {
            if ($pass == $pass2) {
                $database->query("UPDATE users SET password = ?, token = ? WHERE id = ?",
                    [password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $auth->getUserID()]);

                UnorganizedFunctions::Notification("Your password has been changed.", "/login.php");
            } else {
                $error .= " The new passwords aren't identical.";
            }
        } else {
            $error .= "Your current password is incorrect.";
        }
    }
    
    if (strlen($title) > 80) {
        $error .= "Your display name is too long.";
    }

    $username_changed = false;

    if ($currentPass && $new_username) {
        if (password_verify($currentPass, $password)) {
            $old_username = $database->fetch("SELECT name FROM users WHERE id = ?", [$auth->getUserID()])["name"];

            $is_old_username = $database->fetch("SELECT COUNT(*) FROM user_old_names WHERE user = ? AND old_name = ?", [$auth->getUserID(), $new_username]);

            if ($is_old_username) {
                $database->query("INSERT INTO user_old_names (user, old_name, time) VALUES (?, ?, ?)",
                    [$auth->getUserID(), $old_username, time()]);
                $database->query("UPDATE users SET name = ? WHERE id = ?", [$new_username, $auth->getUserID()]);
                $username_changed = true;
            } else {
                $error .= UnorganizedFunctions::validateUsername($new_username, $database);
                if ($database->fetch("SELECT COUNT(*) FROM user_old_names WHERE user != ? AND old_name = ?", [$auth->getUserID(), $new_username])) {
                    $error .= "You cannot use someone else's previous username.";
                }

                if (!$error) {
                    $last_entry_time = $database->fetch("SELECT MAX(time) AS last_time FROM user_old_names WHERE user = ?", [$auth->getUserID()])["last_time"];

                    if (!$last_entry_time || (time() - $last_entry_time >= 2592000)) {
                        $database->query("INSERT INTO user_old_names (user, old_name, time) VALUES (?, ?, ?)",
                            [$auth->getUserID(), $old_username, time()]);
                        $database->query("UPDATE users SET name = ? WHERE id = ?", [$new_username, $auth->getUserID()]);
                        $username_changed = true;
                    } else {
                        $days_left = ceil((2592000 - (time() - $last_entry_time)) / 86400);
                        $error .= "Please wait until $days_left days to change your username.";
                    }
                }
            }
        }
    }

    if (!empty($_FILES['profilePicture']['name'])) {
        $name = $_FILES['profilePicture']['name'];
        $temp_name = $_FILES['profilePicture']['tmp_name'];
        $ext = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
        $storage->uploadProfilePicture($temp_name, $auth->getUserData()["id"]);
    }

    if (!empty($_FILES['profileBanner']['name'])) {
        $name = $_FILES['profileBanner']['name'];
        $temp_name = $_FILES['profileBanner']['tmp_name'];
        $ext = pathinfo($_FILES['profileBanner']['name'], PATHINFO_EXTENSION);
        $storage->uploadProfileBanner($temp_name, $auth->getUserData()["id"]);
    }

    if (!$error) {
        $database->query("UPDATE users SET 
                 title = ?, 
                 about = ?, 
                 comfortable_rating = ?, 
                 customcolor = ?, 
                 blacklisted_tags = ?
                 WHERE id = ?",
            [$title, $about, $rating, $customcolor, json_encode($parsed_tags), $auth->getUserID()]);

        if ($username_changed) {
            // avoids "This user does not exist." error since $auth by this point still uses outdated data.
            // poor design? pretty much, yea. -chaziz 6/18/2024
            $url = "/user/" . $new_username;
        } else {
            $url = "/user/" . $auth->getUserData()["name"];
        }

        UnorganizedFunctions::Notification("Successfully updated your settings!", $url, "success");
    } else {
        UnorganizedFunctions::Notification($error, "/settings.php");
    }
}

echo $twig->render('settings.twig');
