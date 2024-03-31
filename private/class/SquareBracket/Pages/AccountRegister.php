<?php

namespace SquareBracket\Pages;
use Core\Utilities as UtilitiesAlias;
use SquareBracket\Utilities;

/**
 * Backend code for the register page.
 *
 * @since SquareBracket 1.0
 */
class AccountRegister
{
    private \Core\Database $database;
    private \SquareBracket\SquareBracket $orange;

    public function __construct(\SquareBracket\SquareBracket $orange)
    {
        global $disableRegistration;

        if ($disableRegistration) {
            Utilities::Notification("The ability to register on this OpenSB instance has been disabled.", "/");
        }

        $ipcheck = file_get_contents("https://api.stopforumspam.org/api?ip=" . UtilitiesAlias::get_ip_address());

        if (str_contains($ipcheck, "<appears>yes</appears>")) {
            Utilities::Notification("This IP address appears to be suspicious.", "/index.php");
        }

        $this->database = $orange->getDatabase();
        $this->orange = $orange;
    }

    public function postData(array $POST)
    {
        $error = "";

        $username = (string)$POST['username'];
        $pass = $POST['pass1'];
        $pass2 = $POST['pass2'];
        $mail = (string)filter_var($POST['email'], FILTER_SANITIZE_EMAIL);

        if (!isset($username)) $error .= "Blank username.";
        if (!isset($pass2) || $pass != $pass2) $error .= "The passwords don't match.";
        if ($this->database->result("SELECT COUNT(*) FROM users WHERE name = ?", [$username])) $error .= "Username has already been taken. "; //ashley2012 bypassed this -gr 7/26/2021
        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $username)) $error .= "Username contains invalid characters (Only alphanumeric and underscore allowed). "; //ashley2012 bypassed this with the long-ass arabic character. -gr 7/26/2021
        if ($this->database->result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail])) $error .= "You've already registered an account using this email address. ";
        if ($this->database->result("SELECT COUNT(*) FROM users WHERE ip = ?", [UtilitiesAlias::get_ip_address()]) > 10)
            $error .= "Creating more than 10 accounts isn't allowed. ";

        if(!$error) {
            $token = bin2hex(random_bytes(32));
            $this->database->query("INSERT INTO users (name, password, token, joined, lastview, title, email, ip) VALUES (?,?,?,?,?,?,?,?)",
                [$username, password_hash($pass, PASSWORD_DEFAULT), $token, time(), time(), $username, $mail, UtilitiesAlias::get_ip_address()]);

            setcookie('SBTOKEN', $token, 2147483647);

            UtilitiesAlias::redirect('./');
        } else {
            Utilities::Notification($error, "/register.php");
        }
    }
}