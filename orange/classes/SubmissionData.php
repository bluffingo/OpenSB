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
}