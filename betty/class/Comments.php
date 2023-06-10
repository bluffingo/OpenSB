<?php

namespace Betty;

/**
 * Commenting on submissions/profiles.
 *
 * @since 0.1.0
 */
class Comments
{
    private \Betty\Database $database;
    private CommentLocation $type;
    private $id;
    private $data;
    public function __construct(\Betty\Database $database, $type, $id = null) {
        $this->database = $database;
        $this->type = $type;
        $this->id = $id;
    }

    public function getComments() {

        // Submission page, get the submission's comments.
        if ($this->type == CommentLocation::Submission)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c WHERE c.id = ? ORDER BY c.date DESC", [$this->id]));
        }

        // Community page, get the most recent submission comments.
        if ($this->type == CommentLocation::CommunityPage)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c ORDER BY c.date DESC LIMIT 16"));
        }

        $data = [];
        foreach ($database_data as $comment) {
            $author = new User($this->database, $comment["author"]);
            $data[$comment["comment_id"]] = [
                "id" => $comment["comment_id"],
                "posted_id" => $comment["id"],
                "post" => $comment["comment"],
                "posted" => $comment["date"],
                "author" => [
                    "id" => $comment["author"],
                    "info" => $author->getUserArray(),
                ],
            ];

            if ($this->type == CommentLocation::CommunityPage)
            {
                $data[$comment["comment_id"]]["submission_data"] = $this->database->fetch("SELECT title FROM videos WHERE video_id = ?", [$comment["id"]]);
            }
        }
        return $data;
    }
}