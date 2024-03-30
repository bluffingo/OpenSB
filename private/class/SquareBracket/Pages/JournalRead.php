<?php

namespace SquareBracket\Pages;

use SquareBracket\UserData;
use SquareBracket\Utilities;

/**
 * Backend code for the journal reading page.
 *
 * @since SquareBracket 1.0
 */
class JournalRead
{
    private \Core\Database $database;
    private \SquareBracket\SquareBracket $orange;
    private mixed $data;
    private UserData $author;

    public function __construct(\SquareBracket\SquareBracket $orange, $id)
    {
        $this->orange = $orange;
        $this->database = $orange->getDatabase();
        // TODO: JournalData class
        $this->data = $this->database->fetch("SELECT j.* FROM journals j WHERE j.id = ?", [$id]);

        if(!$this->data) {
            Utilities::Notification("This journal does not exist.", "/");
        }

        $this->author = new UserData($this->database, $this->data["author"]);
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