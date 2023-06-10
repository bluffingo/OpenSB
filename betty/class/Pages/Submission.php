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
 * Backend code for the submission view (watch) page.
 *
 * @since 0.1.0
 */
class Submission
{
    private \Betty\Database $database;
    private \Betty\SubmissionData $submission;
    private $data;
    private $comments;
    private $ratings;
    private $favorites;
    private $author;

    /**
     * @throws BettyException
     */
    public function __construct(\Betty\Betty $betty, $id)
    {
        $this->database = $betty->getBettyDatabase();
        $this->submission = new \Betty\SubmissionData($this->database, $id);

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
        if (!$this->author) {
            throw new BettyException('Submission author does not exist.', 500);
        }
        $this->ratings = [
            "1" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$this->data["id"]]),
            "2" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$this->data["id"]]),
            "3" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$this->data["id"]]),
            "4" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$this->data["id"]]),
            "5" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$this->data["id"]]),
        ];
        $this->favorites = $this->database->result("SELECT COUNT(video_id) FROM favorites WHERE video_id=?", [$id]);
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
        $total_ratings = ($this->ratings["1"] +
            $this->ratings["2"] +
            $this->ratings["3"] +
            $this->ratings["4"] +
            $this->ratings["5"]);

        if($total_ratings == 0) {
            $average_ratings = 0;
        } else {
            $average_ratings = ($this->ratings["1"] +
                    $this->ratings["2"] * 2 +
                    $this->ratings["3"] * 3 +
                    $this->ratings["4"] * 4 +
                    $this->ratings["5"] * 5) / $total_ratings;
        }

        $ratings = [
            "stars" => $this->ratings,
            "total" => $total_ratings,
            "average" => $average_ratings,
        ];

        return [
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
                "ratings" => $ratings,
                "favorites" => $this->favorites,
            ],
            "comments" => $this->comments->getComments(),
        ];
    }
}