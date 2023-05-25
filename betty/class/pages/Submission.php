<?php

namespace Betty\Pages;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

class Submission
{
    private \Betty\Database $database;
    private $data;
    private $author;

    public function __construct(\Betty\Betty $betty, $id)
    {
        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.video_id = ?", [$id]);
        if (!$this->data) {
            throw new BettyException('Submission does not exist.', 404);
        }
        $this->author = new User($this->database, $this->data["author"]);
        if (!$this->author) {
            throw new BettyException('Submission author does not exist.', 500);
        }
    }

    public function getSubmission()
    {
        // Return the data for openSB to fuck around with.
        return [
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "type" => $this->data["post_type"],
            "file" => $this->data["videofile"], //FIXME: Port openSB\Videos::getVideoFile()
            "author" => [
                "id" => $this->data["author"],
                "info" => $this->author->getUserArray(),
            ],
            "interactions" => null,
        ];
    }
}