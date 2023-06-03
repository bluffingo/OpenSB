<?php

namespace Betty;

class User
{
    private \Betty\Database $database;
    private $data;
    private $user;

    public function __construct(\Betty\Database $database, $id)
    {
        $this->database = $database;
        $this->data = $this->database->fetch("SELECT u.* FROM users u WHERE u.id = ?", [$id]);
    }

    public function getUserArray(): array
    {
        return [
            "username" => $this->data["name"],
            "displayname" => $this->data["title"],
            "color" => $this->data["customcolor"],
        ];
    }
}