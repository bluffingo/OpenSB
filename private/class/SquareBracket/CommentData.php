<?php

namespace SquareBracket;

/**
 * Commenting on uploads, profiles and journals.
 */
class CommentData
{
    private \SquareBracket\Database $database;
    private CommentLocation $type;
    private $id;
    private $data;
    private $count = 0; // stupid? maybe idfk

    public function __construct(\SquareBracket\Database $database, $type, $id = null) {
        $this->database = $database;
        $this->type = $type;
        $this->id = $id;
    }

    private function fetchComments($query, $params) {
        return $this->database->fetchArray($this->database->query($query, $params));
    }

    // probably stupid and should be part of getComments. -chaziz 8/26/2023
    public function getReplies($comment_id) {
        $database_data = null;

        switch ($this->type) {
            case CommentLocation::Upload:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c WHERE c.reply_to = ? AND c.author NOT IN (SELECT userid FROM bans) ORDER BY c.date ASC", [$comment_id]);
                break;
            case CommentLocation::Profile:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM channel_comments c WHERE c.reply_to = ? AND c.author NOT IN (SELECT userid FROM bans) ORDER BY c.date ASC", [$comment_id]);
                break;
            case CommentLocation::Journal:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM journal_comments c WHERE c.reply_to = ? AND c.author NOT IN (SELECT userid FROM bans) ORDER BY c.date ASC", [$comment_id]);
                break;
        }

        $data = [];
        foreach ($database_data as $comment) {
            $this->count++;
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
                "replies" => $this->getReplies($comment["comment_id"]) // recursive call to get nested replies
            ];
        }
        return $data;
    }

    public function getCommentCount() {
        return $this->count;
    }

    public function getComments() {
        $database_data = null;

        switch ($this->type) {
            case CommentLocation::Upload:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted 
                                                  FROM comments c 
                                                  WHERE c.id = ? AND c.reply_to = 0
                                                  AND c.author NOT IN (SELECT userid FROM bans)
                                                  ORDER BY c.date DESC", [$this->id]);
                break;
            case CommentLocation::Profile:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted 
                                                  FROM channel_comments c 
                                                  WHERE c.id = ? AND c.reply_to = 0
                                                  AND c.author NOT IN (SELECT userid FROM bans)
                                                  ORDER BY c.date DESC", [$this->id]);
                break;
            case CommentLocation::Journal:
                $database_data = $this->fetchComments("SELECT c.comment_id, c.id, c.comment, c.author, c.date, c.deleted 
                                                  FROM journal_comments c 
                                                  WHERE c.id = ? AND c.reply_to = 0
                                                  AND c.author NOT IN (SELECT userid FROM bans)
                                                  ORDER BY c.date DESC", [$this->id]);
                break;
        }

        $data = [];
        foreach ($database_data as $comment) {
            $this->count++;
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
