<?php

namespace SquareBracket\Pages;
use Core\Utilities as UtilitiesAlias;
use SquareBracket\Utilities;

/**
 * Backend code for the login page.
 *
 * @since SquareBracket 1.0
 */
class AccountLogin
{
    private $orange;
    private \Core\Database $database;

    public function __construct(\SquareBracket\SquareBracket $orange)
    {
        $this->orange = $orange;
        $this->database = $orange->getDatabase();
    }

    public function postData(array $POST)
    {
        $error = false;

        $username = ($POST['username'] ?? null);
        $password = ($POST['password'] ?? null);

        if (!$username) $error = true;
        if (!$password) $error = true;

        if (!$error) {
            $logindata = $this->database->fetch("SELECT password,token,ip FROM users WHERE name = ?", [$username]);

            if ($logindata && password_verify($password, $logindata['password'])) {
                // check if the account is from an ip that is in ipbans
                $ipban = $this->database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [$logindata['ip']]);

                if ($ipban) {
                    Utilities::Notification("This account's latest IP address is banned.", "/login.php");
                }

                setcookie('SBTOKEN', $logindata['token'], 2147483647);
                $nid = $this->database->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
                $this->database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), UtilitiesAlias::get_ip_address(), $nid]);

                UtilitiesAlias::redirect('./');
            } else {
                Utilities::Notification("Incorrect credentials.", "/login.php");
            }
        } else {
            Utilities::Notification("Please input your credentials.", "/login.php");
        }
    }
}