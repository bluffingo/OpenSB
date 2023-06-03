<?php

namespace Betty\Pages;

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
        $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.video_id = ?", [$id]);
        if (!$this->data) {
            throw new BettyException('Submission does not exist.', 404);
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
     * Get the submission's file, works for both Qobo mode and local mode.
     *
     * @return array|string
     * @since openSB Beta 3.0
     *
     */
    public function getSubmissionFile(): array|string
    {
        global $isQoboTV, $bunnySettings;
        if ($isQoboTV) {
            if ($this->data['post_type'] == 0) {
                // videofile on videos using bunnycdn are the guid, don't ask me why. -grkb 4/8/2023
                return "https://" . $bunnySettings["streamHostname"] . "/" . $this->data["videofile"] . "/playlist.m3u8";
            } elseif ($this->data['post_type'] == 2) {
                // https://qobo-grkb.b-cdn.net/dynamic/art/f_eKEJNj4bm.png
                return "https://" . $bunnySettings["pullZone"] . $this->data["videofile"];
            }
        } else {
            return $this->data['videofile'];
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
        return [
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "type" => $this->data["post_type"],
            "file" => $this->getSubmissionFile(),
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