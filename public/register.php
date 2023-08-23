<?php

namespace openSB;

global $gump, $betty;

use Br33f\Ga4\MeasurementProtocol\Dto\Event\SignUpEvent;

require_once dirname(__DIR__) . '/private/class/common.php';

$error = '';

if (isset($_POST['registersubmit']) or isset($_POST['terms_agreed'])) {

    $ipcheck = file_get_contents("http://api.stopforumspam.org/api?ip=" . getUserIpAddr());

    if (!str_contains($ipcheck, "<appears>yes</appears>")) {
        error(403, "Your IP looks suspicious.");
        //$orange->Notification("Your IP looks suspicious.", "/register.php");
        //exit();
    }

    $gump->validation_rules([
        "username" => "required|alpha_numeric|max_len,128|min_len,1",
        "pass1"    => "required|max_len,128|min_len,8",
        "pass2"    => "required|equalsfield,pass1",
        "email"    => "required|valid_email|max_len,128",
    ]);

    $gump->filter_rules([
        "username" => "trim",
        "pass1"    => "trim",
        "pass2"    => "trim",
        "email"    => "trim|sanitize_email",
    ]);

    $filter = $gump->run($_POST);

    if ($gump->errors()) {
        $error = $gump->get_errors_array();
    } else {
        $username = (string)$filter['username'];
        $pass = $filter['pass1'];
        $pass2 = $filter['pass2'];
        $mail = (string)filter_var($filter['email'], FILTER_SANITIZE_EMAIL);

        if ($sql->result("SELECT COUNT(*) FROM users WHERE name = ?", [$username])) $error .= __("Username has already been taken. "); //ashley2012 bypassed this -gr 7/26/2021
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $username)) $error .= __("Username contains invalid characters (Only alphanumeric and underscore allowed). "); //ashley2012 bypassed this with the long-ass arabic character. -gr 7/26/2021
        if ($sql->result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail])) $error .= "You've already registered an account using this email address. ";
        if ($sql->result("SELECT COUNT(*) FROM users WHERE ip = ?", [getUserIpAddr()]) > 10)
            $error .= "Creating more than 10 accounts isn't allowed. ";

        $token = bin2hex(random_bytes(32));
        $sql->query("INSERT INTO users (name, password, token, joined, title, email) VALUES (?,?,?,?,?,?)",
            [$username, password_hash($pass, PASSWORD_DEFAULT), $token, time(), $username, mailHash($mail)]);

        setcookie('SBTOKEN', $token, 2147483647);

        redirect('./');
    }
}

$twig = twigloader();
echo $twig->render('register.twig', ['errors' => $error]);
