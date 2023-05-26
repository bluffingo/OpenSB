<?php

namespace Betty\Pages;

use Betty\User;
use Betty\BettyException;
use Betty\Database;

/**
 * Backend code for the index page.
 *
 * @since 0.1.0
 */
class Index
{
    private \Betty\Database $database;
    private $data;

    public function __construct(\Betty\Betty $betty)
    {
        $this->database = $betty->getBettyDatabase();
        $this->data = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v ORDER BY RAND() LIMIT 16"));
    }

    /**
     * Returns an array containing a random list of submissions for the openSB frontend.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getData(): array
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