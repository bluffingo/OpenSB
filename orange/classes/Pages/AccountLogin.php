<?php

namespace Orange\Pages;
use Orange\MiscFunctions;
use Orange\User;

/**
 * Backend code for the login page.
 *
 * @since 0.1.0
 */
class AccountLogin
{
    private \Orange\Database $database;

    public function __construct(\Orange\Orange $betty)
    {
        $this->database = $betty->getBettyDatabase();
    }

    public function postData(array $POST)
    {
        $username = ($POST['username'] ?? null);
        $password = ($POST['password'] ?? null);

        if (!$username) $error = "Please enter your username! ";
        if (!$password) $error = "Please enter your password! ";

        if (empty($error)) {
            $logindata = $this->database->fetch("SELECT password,token FROM users WHERE name = ?", [$username]);
            if ($logindata && password_verify($password, $logindata['password'])) {
                setcookie('SBTOKEN', $logindata['token'], 2147483647);
                $nid = $this->database->result("SELECT id FROM users WHERE token = ?", [$logindata['token']]);
                $this->database->query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), MiscFunctions::get_ip_address(), $nid]);

                MiscFunctions::redirect('./');
            } else {
                $error = __("Incorrect username or password.");
            }
        }
    }
}