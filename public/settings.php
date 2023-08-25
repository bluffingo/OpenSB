<?php

namespace openSB;

use Orange\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

// TODO: should be ported properly

if (!$auth->isUserLoggedIn())
{
    $betty->Notification("Please login to continue.", "/login.php");
}
//if ($auth->getUserBanData()) die("Banned user.");

$error = '';

if (isset($_POST['save'])) {
    global $storage, $sql, $userbandata;

    if ($auth->getUserBanData()) die("Banned user.");

    $title = htmlspecialchars($_POST['title']) ?? null;
    $customcolor = $_POST['customcolor'] ?? '#3e3ecf';
    $about = $_POST['about'] ?? null;

    $resetToken = $_POST['reset_token'] ?? null;

    $currentPass = ($_POST['current_pass'] ?? null);
    $pass = ($_POST['pass'] ?? null);
    $pass2 = ($_POST['pass2'] ?? null);

    $rating = $_POST['rating'] ?? "general";

    if ($currentPass && $pass && $pass2) {
		$password = $sql->fetch("SELECT password FROM users WHERE id = ?", [$userdata['id']])["password"];
        if (password_verify($currentPass, $password)) {
            if ($pass == $pass2) {
                $sql->query("UPDATE users SET password = ?, token = ? WHERE id = ?",
                    [password_hash($pass, PASSWORD_DEFAULT), bin2hex(random_bytes(32)), $userdata['id']]);

                redirect('login.php?new_pass');
            } else {
                $error .= __(" The new passwords aren't identical.");
            }
        } else {
            $error .= __("Your current password is incorrect.");
        }
    }
    if ($error) $error = "The following errors occured while changing your password: " . $error;

    if ($resetToken) {
        $sql->query("UPDATE users SET token = ? WHERE id = ?", [bin2hex(random_bytes(32)), $currentUser['id']]);
        redirect('login.php?new_token');
    }

    // banned users shouldn't be able to change their profile
    if (!$userbandata) {
		if (strlen($title) > 50) {
			$error .= __("Your display name is too long.");
		}
        if (!empty($_FILES['profilePicture']['name'])) {
            $name = $_FILES['profilePicture']['name'];
            $temp_name = $_FILES['profilePicture']['tmp_name'];
            $ext = pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION);
            $target_file = '../dynamic/pfp/' . $userdata['name'] . '.png';
            $storage->uploadImage($temp_name, $target_file, 'png', true, 600, 600);
        }

		if (!$error) {
				$sql->query("UPDATE users SET title = ?, about = ?, customcolor = ?, comfortable_rating = ? WHERE id = ?",
					[$title, $about, $customcolor, $rating, $userdata['id']]);
		}
    }

    if (!$error) {
        redirect(sprintf("user.php?name=%s&edited", $userdata['name']));
    }
}

$twig = new Templating($betty);

echo $twig->render('settings.twig', [
    'error' => $error ?? null,
]);
