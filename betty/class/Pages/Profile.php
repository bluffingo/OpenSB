<?php

namespace Betty\Pages;

use Betty\MiscFunctions;
use Betty\BettyException;
use Betty\CommentLocation;
use Betty\Comments;
use Betty\Database;
use Betty\SubmissionData;

/**
 * Backend code for the profile page.
 *
 * @since 0.1.0
 */
class Profile
{
    private \Betty\Database $database;
    private $data;
    private $is_own_profile;
    public function __construct(\Betty\Betty $betty, $username)
    {
        global $auth;
        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetch("SELECT u.* FROM users u WHERE u.name = ?", [$username]);

        if ($this->database->fetch("SELECT * FROM bans WHERE userid = ?", [$this->data["id"]]))
        {
            $betty->Notification("This user is banned.", "/");
        }

        if ($this->data["id"] == $auth->getUserID())
        {
            $is_own_profile = true;
        }
    }

    public function getData(): array
    {
        return [
            "username" => $this->data["name"],
            "displayname" => $this->data["title"],
            "about" => ($userpagedata['about'] ?? false),
            "color" => $this->data["customcolor"],
            "joined" => $this->data["joined"],
            "connected" => $this->data["lastview"],
        ];
    }
}