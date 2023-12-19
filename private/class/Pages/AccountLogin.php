<?php

namespace Orange\Pages;
use Orange\MiscFunctions;
use Orange\User;

/**
 * Backend code for the login page.
 *
 * @since Orange 1.0
 */
class AccountLogin
{
    private $betty;
    private \Orange\Database $database;

    public function __construct(\Orange\Orange $betty)
    {
        $this->betty = $betty;
        $this->database = $betty->getBettyDatabase();
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
                    $this->betty->Notification("This account's latest IP address is banned.", "/login.php");
                }

                setcookie('SBTOKEN', $logindata['token'], 2147483647);
                $nid = $this->database->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
                $this->database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), MiscFunctions::get_ip_address(), $nid]);

                MiscFunctions::redirect('./');
            } else {
                $this->betty->Notification("Incorrect credentials.", "/login.php");
            }
        } else {
            $this->betty->Notification("Please input your credentials.", "/login.php");
        }
    }
}