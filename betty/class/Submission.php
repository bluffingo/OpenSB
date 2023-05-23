<?php

namespace Betty;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

class Submission
{
    private \Betty\Database $database;
    private $submission;

    public function __construct(\Betty\Betty $betty)
    {
        $this->database = $betty->getBettyDatabase();
    }

    public function getSubmission($id)
    {
        // User class
        $user = new User;

        // Get the submission data
        $data = $this->database->fetch("SELECT v.* FROM videos v JOIN users u WHERE v.video_id = ?", [$id]);
        
        // If the submission doesn't exist.
        if (!$data) {
            throw new BettyException('Submission does not exist.');
        }

        // Set the submission data, might be crappy.
        $this->submission = [
            "id" => $id,
            "title" => $data["title"],
            "description" => $data["description"],
            "published" => $data["time"],
            "type" => $data["post_type"],
            "file" => $data["videofile"],
            "author" => [
                "id" => $data["author"],
                "info" => $user->getUserFromID($data["author"]),
            ],
            "interactions" => null,
        ];

        // Return the data for openSB to fuck around with.
        return $this->submission;
    }
}