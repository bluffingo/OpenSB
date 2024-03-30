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
        global $gump;

        $error = '';

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

        $filter = $gump->run($POST);

        if ($gump->errors()) {
            $error = $gump->get_errors_array();
            $error_message = '';
            foreach ($error as $error_data) {
                $error_message = $error_message . $error_data . '. ';
            }
            Utilities::Notification($error_message, "/register.php");
        } else {
            $username = (string)$filter['username'];
            $pass = $filter['pass1'];
            $pass2 = $filter['pass2'];
            $mail = (string)filter_var($filter['email'], FILTER_SANITIZE_EMAIL);

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
}