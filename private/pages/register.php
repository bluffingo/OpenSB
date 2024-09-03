<?php

namespace OpenSB;

global $disableRegistration, $enableInviteKeys, $twig, $database, $captcha, $isDebug;

use DateTime;
use SquareBracket\UnorganizedFunctions;
use SquareBracket\Utilities;

if ($disableRegistration) {
    UnorganizedFunctions::bannerNotification("The ability to register has been disabled.", "/");
}

$ipcheck = file_get_contents("https://api.stopforumspam.org/api?ip=" . UnorganizedFunctions::getIpAddress());

if (str_contains($ipcheck, "<appears>yes</appears>") && !$isDebug) {
    UnorganizedFunctions::bannerNotification("This IP address appears to be suspicious.", "/index.php");
}

if (isset($_POST['registersubmit'])) {
    $error = "";

    if ($captcha["enabled"]) {
        $verify = curl_init();
        curl_setopt($verify, CURLOPT_URL,   "https://hcaptcha.com/siteverify");
        curl_setopt($verify, CURLOPT_POST, true);
        curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $captcha['secret'],
            'response' => $_POST['h-captcha-response']
        ]));
        curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
        $verify = curl_exec($verify);
        $verify = json_decode($verify, true);

        if (!$verify['success']) {
            $error .= "You must complete the captcha in order to register a new account. ";
        }
    }

    $username = trim($_POST['username'] ?? '');
    $pass = $_POST['pass1'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';
    $mail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $birthdate = $_POST['birthdate'] ?? '';
    if ($enableInviteKeys) {
        $invite = $_POST['invite'];
    }

    $error .= UnorganizedFunctions::validateUsername($username, $database);
    if ($database->result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail]) > 0) $error .= "This email address is used by another account. ";
    if (!isset($pass2) || $pass != $pass2) $error .= "The passwords don't match. ";
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $error .= "Invalid email format. ";
    if ((UnorganizedFunctions::getIpAddress() != "127.0.0.1") && (UnorganizedFunctions::getIpAddress() != "::1")) {
        if ($database->result("SELECT COUNT(*) FROM users WHERE ip = ?", [UnorganizedFunctions::getIpAddress()]) > 3)
            $error .= "Your IP address has too many accounts associated with it. ";
    }
    if ($database->fetch("SELECT COUNT(*) FROM user_old_names WHERE old_name = ?", [$username])["COUNT(*)"] >= 1)
        $error .= "You cannot use someone's previous username. ";

    $dobDateTime = new DateTime($birthdate);
    $currentDate = new DateTime();

    $age = $currentDate->diff($dobDateTime)->y;

    if ($age < 13) {
        $error .= "You are below the age of 13. ";
    }

    if ($enableInviteKeys) {
        $inviteValidationResult = $database->result("SELECT id FROM invite_keys WHERE invite_key = ? AND claimed_by IS NULL", [$invite]);
        if (empty($invite) || !$inviteValidationResult) {
            $error .= "Invalid or missing invite key. ";
        }
    }

    if(!$error) {
        $token = bin2hex(random_bytes(32));
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
        $database->query("INSERT INTO users (name, password, token, joined, lastview, title, email, ip, birthdate)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$username, $hashedPassword, $token, time(), time(), $username, $mail, UnorganizedFunctions::getIpAddress(), $dobDateTime->format('Y-m-d')]);
        $userId = $database->insertId();

        if ($enableInviteKeys) {
            $database->query("UPDATE invite_keys SET claimed_by = ?, claimed_time = ? WHERE invite_key = ?", [$userId, time(), $invite]);
        }

        $_SESSION["SBTOKEN"] = $token;

        UnorganizedFunctions::redirect('./');
    } else {
        UnorganizedFunctions::bannerNotification($error, "/register.php");
    }
}

$data = [];

if ($captcha['enabled']) {
    $data['captcha_public_token'] = $captcha['public'];
}

echo $twig->render('register.twig', $data);