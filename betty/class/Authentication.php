<?php

namespace Betty;

/**
 * Authentication stuff.
 *
 * @since 0.1.0
 */
class Authentication
{
    private \Betty\Database $database;
    private bool $is_logged_in;
    private int $user_id;

    public function __construct(\Betty\Database $database, $token)
    {
        $this->database = $database;
        if (isset($token)) {
            if($this->user_id = $this->database->result("SELECT id FROM users WHERE token = ?", [$token])) {
                $this->is_logged_in = true;
            } else {
                $this->is_logged_in = false;
            }
        } else {
            $this->is_logged_in = false;
        }
    }

    public function isUserLoggedIn(): bool
    {
        return $this->is_logged_in;
    }

    public function getUserID(): bool
    {
        return $this->user_id ?? 0;
    }
}