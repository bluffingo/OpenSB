<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\OrangeException;
use Orange\CommentLocation;
use Orange\Comments;
use Orange\Database;
use Orange\SubmissionData;

/**
 * Backend code for the journal reading page.
 *
 * @since 0.1.0
 */
class JournalRead
{
    private \Orange\Database $database;
    private \Orange\Orange $orange;
    private array $data;
    private User $author;

    public function __construct(\Orange\Orange $betty, $id)
    {
        $this->orange = $betty;
        $this->database = $betty->getBettyDatabase();
        // TODO: JournalData class
        $this->data = $this->database->fetch("SELECT j.* FROM journals j WHERE j.id = ?", [$id]);
        $this->author = new User($this->database, $this->data["author"]);
    }

    public function getData()
    {
        global $auth;
        if ($auth->getUserID() == $this->data["author"]) { $owner = true; } else { $owner = false; }

        return [
            "is_owner" => $owner,
            "int_id" => $this->data["id"],
            "title" => $this->data["title"],
            "contents" => $this->data["post"],
            "published" => $this->data["date"],
            "author" => [
                "id" => $this->data["author"],
                "info" => $this->author->getUserArray(),
            ],
        ];
    }
}