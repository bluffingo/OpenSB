<?php

namespace SquareBracket;

/**
 * Submissions.
 *
 * @since SquareBracket 1.0
 */
class SubmissionData
{
    private \SquareBracket\Database $database;
    private $takedown;
    private $data;
    private $tags;

    // FLAGS

    // 00000001: featured submission, at least back in the opensb milestone/alpha 2 days.
    public const FLAG_FEATURED = 1;

    // 00000010: Unprocessed VIDEO submission, which is now unused but a few image submissions on squarebracket still have
    // this flag toggled.
    public const FLAG_UNPROCESSED = 2;

    // 00000100: "Block guests from viewing this submission"
    public const FLAG_BLOCK_GUESTS = 4;

    // 00001000: "Block users from commenting in this submission"
    public const FLAG_BLOCK_COMMENTS = 8;

    // 00010000: "Submission has custom thumbnail"
    public const FLAG_CUSTOM_THUMBNAIL = 16;

    public function __construct(\SquareBracket\Database $database, $id)
    {
        $this->database = $database;

        // if we get the internal id instead of the string id, we correct $id after fetching the submission otherwise
        // stuff won't work.
        if (is_int($id)) {
            $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.id = ?", [$id]);
            if ($this->data != []) {
                $id = $this->data["video_id"];
            }
        } else {
            $this->data = $this->database->fetch("SELECT v.* FROM videos v WHERE v.video_id = ?", [$id]);
        }
        if ($this->data != []) {
            $this->takedown = $this->database->fetch("SELECT * FROM takedowns t WHERE t.submission = ?", [$id]);
            $this->tags = $this->database->fetchArray($this->database->query("SELECT * FROM `tag_index` ti JOIN tag_meta t ON (t.tag_id = ti.tag_id) WHERE ti.video_id = ?", [$this->data["id"]]));
        }
    }

    public function getTakedown()
    {
        return $this->takedown;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function bitmaskToArray()
    {
        if ($this->data != []) {
            return [
                "featured" => (bool)($this->data["flags"] & $this::FLAG_FEATURED),
                "unprocessed" => (bool)($this->data["flags"] & $this::FLAG_UNPROCESSED),
                "block_guests" => (bool)($this->data["flags"] & $this::FLAG_BLOCK_GUESTS),
                "block_comments" => (bool)($this->data["flags"] & $this::FLAG_BLOCK_COMMENTS),
                "custom_thumbnail" => (bool)($this->data["flags"] & $this::FLAG_CUSTOM_THUMBNAIL),
            ];
        }
    }
}