<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\BettyException;
use Orange\CommentLocation;
use Orange\Comments;
use Orange\Database;
use Orange\SubmissionData;

/**
 * Backend code for the submission view (watch) page.
 *
 * @since 0.1.0
 */
class Submission
{
    private \Orange\Database $database;
    private \Orange\SubmissionData $submission;
    private $data;
    private $comments;
    private $ratings;
    private $favorites;
    private $author;
    private $views;
    private $bools;

    /**
     * @throws BettyException
     */
    public function __construct(\Orange\Orange $betty, $id)
    {
        global $auth; // honestly i feel like the whole "getBettyDatabase" shit is so redudant -chaziz 8/23/2023

        $this->database = $betty->getBettyDatabase();
        $this->submission = new \Orange\SubmissionData($this->database, $id);

        // check if the submission has been taken down.
        $takedown = $this->submission->getTakedown();
        if ($takedown) {
            // don't load if it has been taken down.
            $betty->Notification("This submission has been taken down. (" . $takedown["reason"] . ")", "/");
        }

        $this->data = $this->submission->getData();
        if (!$this->data) {
            $betty->Notification("This submission does not exist.", "/");
        }
        $this->comments = new Comments($this->database, CommentLocation::Submission, $id);
        $this->author = new User($this->database, $this->data["author"]);
        if ($this->author->isUserBanned()) {
            $betty->Notification("This submission's author is banned.", "/");
        }

        $this->ratings = [
            "1" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$this->data["id"]]),
            "2" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$this->data["id"]]),
            "3" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$this->data["id"]]),
            "4" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$this->data["id"]]),
            "5" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$this->data["id"]]),
        ];
        $this->favorites = $this->database->result("SELECT COUNT(video_id) FROM favorites WHERE video_id=?", [$id]);

        $this->views = $this->database->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$this->data["video_id"]])['COUNT(video_id)'];

        $this->bools = $this->submission->bitmaskToArray();

        if ($this->bools["block_guests"] && !$auth->isUserLoggedIn())
        {
            $betty->Notification("This submission's author has blocked guest access.", "/login.php");
        }
    }

    /**
     * Returns an array containing the submission for the openSB frontend.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getSubmission(): array
    {
        global $auth;
        if ($auth->getUserID() == $this->data["author"]) { $owner = true; } else { $owner = false; }

        return [
            "is_owner" => $owner,
            "int_id" => $this->data["id"],
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "type" => $this->data["post_type"],
            "file" => MiscFunctions::getSubmissionFile($this->data),
            "author" => [
                "id" => $this->data["author"],
                "info" => $this->author->getUserArray(),
            ],
            "interactions" => [
                "views" => $this->views,
                "ratings" => MiscFunctions::calculateRatings($this->ratings),
                "favorites" => $this->favorites,
            ],
            "comments" => $this->comments->getComments(),
            "bools" => $this->bools,
        ];
    }
}