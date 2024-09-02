<?php

namespace SquareBracket;

class UserData
{
    private \SquareBracket\Database $database;
    private $data;
    private $followers;
    private $is_banned;

    public function __construct(\SquareBracket\Database $database, $id)
    {
        $this->database = $database;
        $this->data = $this->database->fetch("SELECT u.* FROM users u WHERE u.id = ?", [$id]);
        $this->followers = $this->database->fetch("SELECT COUNT(user) FROM subscriptions WHERE user = ?", [$id])['COUNT(user)'];
        $this->is_banned = $this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$id]);
        if ($this->data == null) {
            trigger_error("User ID $id is nonexistent.", E_USER_WARNING);
        }
    }

    public function isUserBanned()
    {
        if ($this->is_banned) { return true; }
        return false;
    }

    public function getUserArray(): array
    {
        if ($this->data) {
            return [
                "username" => $this->data["name"],
                "displayname" => $this->data["title"],
                "color" => $this->data["customcolor"],
                "followers" => $this->followers,
                "joined" => $this->data["joined"],
                "connected" => $this->data["lastview"],
                "customcolor" => $this->data["customcolor"],
            ];
        } else {
            return [
                "username" => "InvalidUser!",
                "displayname" => "Invalid user!",
                "color" => "#FF0000",
                "followers" => 0,
                "joined" => 0,
                "connected" => 0,
                "customcolor" => "#FF0000",
            ];
        }
    }
}