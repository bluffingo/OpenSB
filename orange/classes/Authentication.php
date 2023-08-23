<?php

namespace Orange;

/**
 * Authentication stuff.
 *
 * @since 0.1.0
 */
class Authentication
{
    private \Orange\Database $database;
    private bool $is_logged_in;
    private int $user_id;
    private array $user_data;
    private $user_ban_data;
    private $user_notice_count; // this shouldn't be here but whatever

    public function __construct(\Orange\Database $database, $token)
    {
        $accountfields = "id, name, email, customcolor, title, about, powerlevel, joined, lastview";
        $this->database = $database;
        if (isset($token)) {
            if($this->user_id = $this->database->result("SELECT id FROM users WHERE token = ?", [$token])) {
                $this->is_logged_in = true;
                $this->user_data = $this->database->fetch("SELECT $accountfields FROM users WHERE id = ?", [$this->user_id]);
                $this->user_notice_count = $this->database->result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$this->user_id]);
                $this->user_ban_data = $this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$this->user_id]);
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

    public function getUserID(): ?int
    {
        if ($this->is_logged_in) {
            return $this->user_id;
        } else {
            return null;
        }
    }

    public function getUserData(): ?array
    {
        if ($this->is_logged_in) {
            return $this->user_data;
        } else {
            return null;
        }
    }

    public function getUserNoticesCount(): ?int
    {
        if ($this->is_logged_in) {
            return $this->user_notice_count;
        } else {
            return 0;
        }
    }

    public function getUserBanData()
    {
        return $this->user_ban_data;
    }
}