<?php

namespace Betty\Pages;

use Betty\MiscFunctions;
use Betty\User;
use Betty\BettyException;
use Betty\CommentLocation;
use Betty\Comments;
use Betty\Database;
use Betty\SubmissionData;

/**
 * Backend code for the submission modification page.
 *
 * @since 0.1.0
 */
class SubmissionEdit
{
    private \Betty\Database $database;
    private \Betty\SubmissionData $submission;

    public function __construct(\Betty\Betty $betty, $id)
    {
        global $auth;
        $this->id = $id;
        $this->betty = $betty;
        $this->database = $betty->getBettyDatabase();
        $this->submission = new \Betty\SubmissionData($this->database, $id);
        $this->data = $this->submission->getData();

        if ($auth->getUserBanData() || $this->submission->getTakedown()) {
            $betty->Notification("You cannot proceed with this action.", "/");
        }

        if ($auth->getUserID() != $this->data["author"]) {
            $betty->Notification("This is not your video.", "/");
        }

    }

    /**
     * Returns an array containing the submission for the openSB frontend.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            "int_id" => $this->data["id"],
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "type" => $this->data["post_type"],
        ];
    }

    public function postData($data)
    {
        $title = $data['title'] ?? null;
        $desc = $data['desc'] ?? null;

        $this->database->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
            [$title, $desc, $this->id]);
        $this->betty->Notification("Your submission's information has been modified.", "/watch?v=" . $this->id, "success");
    }
}