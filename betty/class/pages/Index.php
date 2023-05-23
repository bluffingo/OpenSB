<?php

namespace Betty\Pages;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

class Index
{
    private \Betty\Database $database;
    private $data;

    public function __construct(\Betty\Betty $betty)
    {
        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v ORDER BY RAND() LIMIT 16"));
    }

    public function getIndexData()
    {
        $indexData = [];
        foreach ($this->data as $submission) {
            $indexData[] =
                [
                    "id" => $submission["video_id"],
                    "title" => $submission["title"],
                    "description" => $submission["description"],
                    "published" => $submission["time"],
                    "type" => $submission["post_type"],
                    "author" => [
                        "id" => $submission["author"],
                    ],
                ];
            }
        return $indexData;
    }
}