<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * Backend code for the submission modification page.
 *
 * @since SquareBracket 1.0
 */
class SubmissionEdit
{
    private \SquareBracket\Database $database;
    private \SquareBracket\SubmissionData $submission;
    private mixed $id;
    private \SquareBracket\SquareBracket $orange;
    private mixed $data;

    public function __construct(\SquareBracket\SquareBracket $orange, $id)
    {
        global $auth;
        $this->id = $id;
        $this->orange = $orange;
        $this->database = $orange->getDatabase();
        $this->submission = new \SquareBracket\SubmissionData($this->database, $id);
        $this->data = $this->submission->getData();

        if (!$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
        }

        if ($auth->getUserBanData() || $this->submission->getTakedown()) {
            UnorganizedFunctions::Notification("You cannot proceed with this action.", "/");
        }

        if ($auth->getUserID() != $this->data["author"]) {
            UnorganizedFunctions::Notification("This is not your submission.", "/");
        }
    }

    /**
     * Returns an array containing the submission for the openSB frontend.
     *
     * @since SquareBracket 1.0
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
            $storage->uploadCustomThumbnail($temp_name, $target_file);
        }

        $this->database->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
            [$title, $desc, $this->id]);
        UnorganizedFunctions::Notification("Your submission's details has been modified.", "/view/" . $this->id, "success");
    }
}