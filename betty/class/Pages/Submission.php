<?php

namespace Betty\Pages;

use Betty\MiscFunctions;
use Betty\User;
use Betty\BettyException;
use Betty\CommentLocation;
use Betty\Comments;
use Betty\Database;

/**
 * Backend code for the submission view (watch) page.
 *
 * @since 0.1.0
 */
class Submission
{
    private \Betty\Database $database;
    private $data;
    private $comments;
    private $likes;
    private $dislikes;
    private $favorites;
    private $author;

    /**
     * @throws BettyException
     */
    public function __construct(\Betty\Betty $betty, $id)
    {
        $this->database = $betty->getBettyDatabase();

        // check if the submission has been taken down.
        $takedown = $this->database->fetch("SELECT * FROM takedowns t WHERE t.submission = ?", [$id]);
        if ($takedown) {
            // don't load if it has been taken down.
            $betty->Notification("This submission has been taken down. (" . $takedown["reason"] . ")", "/");
        }

        $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.video_id = ?", [$id]);
        if (!$this->data) {
            $betty->Notification("This submission does not exist.", "/");
        }
        $this->comments = new Comments($this->database, CommentLocation::Submission, $id);
        $this->author = new User($this->database, $this->data["author"]);
        if (!$this->author) {
            throw new BettyException('Submission author does not exist.', 500);
        }
        $this->likes = $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$id]);
        $this->dislikes = $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$id]);
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
                "likes" => $this->likes,
                "dislikes" => $this->dislikes,
                "favorites" => $this->favorites,
            ],
            "comments" => $this->comments->getComments(),
        ];
    }
}