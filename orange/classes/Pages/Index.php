<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\BettyException;
use Orange\Database;

/**
 * Backend code for the index page.
 *
 * @since 0.1.0
 */
class Index
{
    private \Orange\Database $database;
    private array $submissions;
    private array $posts;

    public function __construct(\Orange\Orange $betty)
    {
        $this->database = $betty->getBettyDatabase();
        $this->submissions = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) ORDER BY RAND() LIMIT 16"));
        $this->posts = $this->database->fetchArray($this->database->query("SELECT * FROM posts LIMIT 16"));
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
        $submissionsData = [];
        foreach ($this->submissions as $submission) {

            $ratingData = [
                "1" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$submission["id"]]),
                "2" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=2", [$submission["id"]]),
                "3" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=3", [$submission["id"]]),
                "4" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=4", [$submission["id"]]),
                "5" => $this->database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=5", [$submission["id"]]),
            ];

            $userData = new User($this->database, $submission["author"]);
            $submissionsData[] =
                [
                    "id" => $submission["video_id"],
                    "title" => $submission["title"],
                    "description" => $submission["description"],
                    "published" => $submission["time"],
                    "type" => $submission["post_type"],
                    "author" => [
                        "id" => $submission["author"],
                        "info" => $userData->getUserArray(),
                    ],
                    "interactions" => [
                        "ratings" => MiscFunctions::calculateRatings($ratingData),
                    ],
                ];
            }

        $postsData = [];
        foreach ($this->posts as $post) {
            $userData = new User($this->database, $post["author"]);
            $postsData[] =
                [
                    "post" => $post["post"],
                    "posted" => $post["date"],
                    "author" => [
                        "id" => $post["author"],
                        "info" => $userData->getUserArray(),
                    ],
                ];
        }
        return [
            "submissions" => $submissionsData,
            "posts" => $postsData,
        ];
    }
}