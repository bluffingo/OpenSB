<?php

namespace OpenSB;

global $twig, $database, $auth, $orange;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\Utilities;

$warning = $orange->getWarningString();

$path_username = $path[2] ?? null;

if (isset($path_username)) {
    if (isset($_POST["loginsubmit"])) {
        die("?????");
    }

    $is_the_account_in_the_accounts_array = false;
    $id = UnorganizedFunctions::usernameToID($database, $path_username);
    $accounts = $orange->getAccountsArray();
    $new_array = [];
    $token = null;

    // stupid shit
    foreach ($accounts as $account) {
        if ($account["userid"] == $id) {
            $is_the_account_in_the_accounts_array = true;
            $token = $account["token"];
            $new_array[] = [
                "userid" => $auth->getUserID(),
                "token" => $_COOKIE["SBTOKEN"],
            ];
        } else {
            $new_array[] = $account;
        }
    }

    if ($is_the_account_in_the_accounts_array) {
        setcookie('SBTOKEN', $token, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' =>'Lax',
        ]);

        $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($new_array)));

        setcookie('SBACCOUNTS', $encoded_sbaccounts_cookie, [
            'expires' => time() + (30 * 24 * 60 * 60),
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'samesite' =>'Lax',
        ]);
        UnorganizedFunctions::Notification("Switched to $path_username.", '/', "success");
    } else {
        UnorganizedFunctions::Notification("You have not logged into this account.", '/');
    }
}

if (isset($_POST["loginsubmit"])) {

    $error = false;

    $username = ($_POST['username'] ?? null);
    $password = ($_POST['password'] ?? null);
    $rememberMe = isset($_POST['remember_me']);

    if (!$username) $error = true;
    if (!$password) $error = true;

    if ($username == $auth->getUserData()["name"]) {
        UnorganizedFunctions::Notification("You're already logged into this account.", "/");
    }

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

            // if we're logged in, add our current token in an array for account switching purposes.
            if (isset($_COOKIE["SBTOKEN"])) {
                if (!isset($_COOKIE["SBACCOUNTS"])) {
                    $current_userid = $auth->getUserID();

                    $cookie_shit_testing = [
                        [
                            "userid" => $current_userid,
                            "token" => $_COOKIE["SBTOKEN"],
                        ]
                    ];

                    $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($cookie_shit_testing)));
                } else {
                    // TODO: this will be buggy, i can feel it. -chaziz 6/28/2024
                    $stupid_fucking_bullshit = str_replace($warning, "", $_COOKIE["SBACCOUNTS"]);
                    $decoded_accounts = json_decode(base64_decode($stupid_fucking_bullshit));

                    $current_userid = $auth->getUserID();

                    $decoded_accounts[] = [
                        "userid" => $current_userid,
                        "token" => $_COOKIE["SBTOKEN"],
                    ];

                    $encoded_sbaccounts_cookie = ($warning . base64_encode(json_encode($decoded_accounts)));
                }

                setcookie('SBACCOUNTS', $encoded_sbaccounts_cookie, [
                    'expires' => $expires,
                    'path' => '/',
                    'domain' => '',
                    'secure' => false,
                    'httponly' => false,
                    'samesite' =>'Lax',
                ]);
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
