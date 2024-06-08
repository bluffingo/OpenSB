<?php

namespace SquareBracket;

/**
 * Commenting on submissions/profiles.
 *
 * @since SquareBracket 1.0
 */
class CommentData
{
    private \SquareBracket\Database $database;
    private CommentLocation $type;
    private $id;
    private $data;
    public function __construct(\SquareBracket\Database $database, $type, $id = null) {
        $this->database = $database;
        $this->type = $type;
        $this->id = $id;
    }

    // probably stupid and should be part of getComments. -chaziz 8/26/2023
    public function getReplies($comment_id) {
        $database_data = null;

        // Submission view page.
        if ($this->type == CommentLocation::Submission)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c WHERE c.reply_to = ? ORDER BY c.date DESC", [$comment_id]));
        }

        // Profile page.
        if ($this->type == CommentLocation::Profile)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM channel_comments c WHERE c.reply_to = ? ORDER BY c.date DESC", [$comment_id]));
        }

        // Journal read page.
        if ($this->type == CommentLocation::Journal)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM journal_comments c WHERE c.reply_to = ? ORDER BY c.date DESC", [$comment_id]));
        }

        $data = [];
        foreach ($database_data as $comment) {
            $author = new UserData($this->database, $comment["author"]);
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
        }
        return $data;
    }

    public function getComments() {

        $database_data = null;

        // Submission view page.
        if ($this->type == CommentLocation::Submission)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c WHERE c.id = ? ORDER BY c.date DESC", [$this->id]));
        }

        // Profile page.
        if ($this->type == CommentLocation::Profile)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM channel_comments WHERE reply_to = c.comment_id) AS replycount FROM channel_comments c WHERE c.id = ? ORDER BY c.date DESC", [$this->id]));
        }

        // Journal read page.
        if ($this->type == CommentLocation::Journal)
        {
            $database_data = $this->database->fetchArray($this->database->query("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM journal_comments WHERE reply_to = c.comment_id) AS replycount FROM journal_comments c WHERE c.id = ? ORDER BY c.date DESC", [$this->id]));
        }

        $data = [];
        foreach ($database_data as $comment) {
            $author = new UserData($this->database, $comment["author"]);
            $data[$comment["comment_id"]] = [
                "id" => $comment["comment_id"],
                "posted_id" => $comment["id"],
                "post" => $comment["comment"],
                "posted" => $comment["date"],
                "author" => [
                    "id" => $comment["author"],
                    "info" => $author->getUserArray(),
                ],
                "replies" => $this->getReplies($comment["comment_id"]),
            ];
        }
        return $data;
    }
}