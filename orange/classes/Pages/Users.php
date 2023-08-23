<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\Database;

/**
 * Backend code for the users list page.
 *
 * @since 0.1.0
 */
class Users
{
    private \Orange\Database $database;

    public function __construct(\Orange\Orange $betty)
    {
        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT u.id, (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num FROM users u"));
    }

    public function getData()
    {
        $usersData = [];
        foreach ($this->data as $user)
        {
            $userData = new User($this->database, $user["id"]);
            $usersData[] =
                [
                    "id" => $user["id"],
                    "info" => $userData->getUserArray(),
                    "submissions" => $user["s_num"],
                ];
        }
        return($usersData);
    }
}