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
 * Backend code for the submission modification page.
 *
 * @since Orange 1.0
 */
class SubmissionEdit
{
    private \Orange\Database $database;
    private \Orange\SubmissionData $submission;
    private mixed $id;
    private \Orange\Orange $betty;
    private mixed $data;

    public function __construct(\Orange\Orange $betty, $id)
    {
        global $auth;
        $this->id = $id;
        $this->betty = $betty;
        $this->database = $betty->getBettyDatabase();
        $this->submission = new \Orange\SubmissionData($this->database, $id);
        $this->data = $this->submission->getData();

        if (!$auth->isUserLoggedIn())
        {
            $betty->Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData() || $this->submission->getTakedown()) {
            $betty->Notification("You cannot proceed with this action.", "/");
        }

        if ($auth->getUserID() != $this->data["author"]) {
            $betty->Notification("This is not your submission.", "/");
        }
    }

    /**
     * Returns an array containing the submission for the openSB frontend.
     *
     * @since Orange 1.0
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
        global $storage;

        $title = $data['title'] ?? null;
        $desc = $data['desc'] ?? null;

        if (!empty($_FILES['thumbnail']['name'])) {
            $name = $_FILES['thumbnail']['name'];
            $temp_name = $_FILES['thumbnail']['tmp_name'];
            $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $target_file = '../dynamic/custom_thumbnails/' . $this->data["video_id"] . '.jpg';
            $storage->uploadImage($temp_name, $target_file, 'jpg', false, 1280);
        }

        $this->database->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
            [$title, $desc, $this->id]);
        $this->betty->Notification("Your submission's details has been modified.", "/watch?v=" . $this->id, "success");
    }
}