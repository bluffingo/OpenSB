<?php

namespace Orange;

/**
 * Submissions.
 *
 * @since 0.1.0
 */
class SubmissionData
{
    private \Orange\Database $database;
    private $takedown;
    private $data;

    // FLAGS

    // 00000001: featured submission, at least back in the squarebracket milestone/alpha 2 days.
    public const FLAG_FEATURED = 1;

    // 00000010: Unprocessed VIDEO submission, which is now unused but a few image submissions on qobo still have
    // this flag toggled.
    public const FLAG_UNPROCESSED = 2;

    // 00000100: "Block guests from viewing this submission"
    public const FLAG_BLOCK_GUESTS = 4;

    // 00001000: "Block users from commenting in this submission"
    public const FLAG_BLOCK_COMMENTS = 8;

    public function __construct(\Orange\Database $database, $id)
    {
        $this->database = $database;
        $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.video_id = ?", [$id]);
        $this->takedown = $this->database->fetch("SELECT * FROM takedowns t WHERE t.submission = ?", [$id]);
    }

    public function getTakedown()
    {
        return $this->takedown;
    }

    public function getData()
    {
        return $this->data;
    }

    public function bitmaskToArray()
    {
        return [
            "featured" => (bool)($this->data["flags"] & $this::FLAG_FEATURED),
            "unprocessed" => (bool)($this->data["flags"] & $this::FLAG_UNPROCESSED),
            "block_guests" => (bool)($this->data["flags"] & $this::FLAG_BLOCK_GUESTS),
            "block_comments" => (bool)($this->data["flags"] & $this::FLAG_BLOCK_COMMENTS),
        ];
    }
}