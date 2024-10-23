<?php

namespace OpenSB;

global $twig, $database, $auth, $orange;

use SquareBracket\Utilities;

$warning = $orange->getWarningString();

$path_username = $path[2] ?? null;

if (isset($path_username)) {
    if (isset($_POST["loginsubmit"])) {
        die("?????");
    }

    $is_the_account_in_the_accounts_array = false;
    $id = Utilities::usernameToID($database, $path_username);
    $accounts = $orange->getAccountsArray();
    $new_array = [];
    $token = null;

    // stupid shit
    foreach ($accounts as $account) {
        if ($account["userid"] == $id) {
            if (!$is_the_account_in_the_accounts_array) {
                $is_the_account_in_the_accounts_array = true;
                $token = $account["token"];
                $new_array[] = [
                    "userid" => $auth->getUserID(),
                    "token" => $_SESSION["SBTOKEN"],
                ];
            }
        } else {
            $new_array[] = $account;
        }
    }

    if ($is_the_account_in_the_accounts_array) {
        $_SESSION["SBTOKEN"] = $token;

        $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($new_array)));

        setcookie('SBACCOUNTS', $encoded_sbaccounts_cookie, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'samesite' =>'Lax',
        ]);
        Utilities::bannerNotification("Successfully switched to $path_username.", '/', "success");
    } else {
        Utilities::bannerNotification("You have not logged into this account.", '/');
    }
}

if (isset($_POST["loginsubmit"])) {
    $error = false;

    $username = ($_POST['username'] ?? null);
    $password = ($_POST['password'] ?? null);

    // backwards compatibility with youclipped
    if ($username !== null) {
        $username = str_replace(' ', '_', $username);
    }

    if (!$username) $error = true;
    if (!$password) $error = true;

    if ($auth->isUserLoggedIn() && $username == $auth->getUserData()["name"]) {
        Utilities::bannerNotification("You're already logged into this account.", "/");
    }

    if (!$error) {
        $logindata = $database->fetch("SELECT password,token,ip,id FROM users WHERE name = ?", [$username]);

        if ($logindata && password_verify($password, $logindata['password'])) {
            // check if the account is from an ip that is in ipbans
            $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [$logindata['ip']]);

            if ($ipban) {
                Utilities::bannerNotification("This account's latest IP address is banned.", "/login");
            }

            // if we're logged in, add our current token in an array for account switching purposes.
            if (isset($_SESSION["SBTOKEN"])) {
                if (!isset($_COOKIE["SBACCOUNTS"])) {
                    $current_userid = $auth->getUserID();

                    $cookie_shit_testing[] = [
                        "userid" => $current_userid,
                        "token" => $_SESSION["SBTOKEN"],
                    ];

                    $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($cookie_shit_testing)));
                } else {
                    // TODO: this will be buggy, i can feel it. -chaziz 6/28/2024
                    // FIXME: and yes it is! duplicate accounts. i kinda dont care tho. -chaziz 8/23/2024
                    $stupid_fucking_bullshit = str_replace($warning, "", $_COOKIE["SBACCOUNTS"]);
                    $decoded_accounts = json_decode(base64_decode($stupid_fucking_bullshit), true);

                    $current_userid = $auth->getUserID();

                    $duplicates = array_keys(array_combine(array_keys($decoded_accounts), array_column($decoded_accounts, 'userid')),$logindata["id"]);

                    foreach ($duplicates as $duplicate) {
                        unset ($decoded_accounts[$duplicate]);
                    }

                    if ($current_userid != $logindata["id"]) {
                        $decoded_accounts[] = [
                            "userid" => $current_userid,
                            "token" => $_SESSION["SBTOKEN"],
                        ];
                    }

                    $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($decoded_accounts)));
                }

                setcookie('SBACCOUNTS', $encoded_sbaccounts_cookie, [
                    'expires' => time() + (30 * 24 * 60 * 60),
                    'path' => '/',
                    'domain' => '',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' =>'Lax',
                ]);

                // null access to admin panel for security
                $_SESSION["SB_ADMIN_AUTHED"] = null;
            }

            $_SESSION["SBTOKEN"] = $logindata['token'];

            $nid = $database->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
            $database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), Utilities::getIpAddress(), $nid]);

            Utilities::redirect('./');
        } else {
            Utilities::bannerNotification("Incorrect credentials.", "/login");
        }
    } else {
        Utilities::bannerNotification("Please input your credentials.", "/login");
    }
}

echo $twig->render('login.twig');
