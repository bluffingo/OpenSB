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
    public function __construct(\Betty\Database $database, $type, $id) {
        $this->database = $database;
        $this->type = $type;
        $this->id = $id;
    }

    public function getComments() {
        if ($this->type == CommentLocation::Submission)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c WHERE c.id = ? ORDER BY c.date DESC", [$this->id]));
        }
        $data = [];
        foreach ($database_data as $comment) {
            $author = new User($this->database, $comment["author"]);
            $data[] = [
                "id" => $comment["id"],
                "post" => $comment["comment"],
                "posted" => $comment["date"],
                "author" => [
                    "id" => $comment["author"],
                    "info" => $author->getUserArray(),
                ],
            ];
        }
        return $data;
    }
}