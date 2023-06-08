<?php

namespace Betty\Pages;

use Betty\MiscFunctions;
use Betty\User;

/**
 * Backend code for the community page.
 *
 * @since 0.1.0
 */
class Community
{
    private \Betty\Database $database;
    private array $suggestions;
    private $random_submission;
    public function __construct(\Betty\Betty $betty)
    {
        $this->database = $betty->getBettyDatabase();
        $this->suggestions = $this->database->fetchArray($this->database->query("SELECT * FROM suggestions ORDER BY RAND() LIMIT 5"));
        $this->random_submission = $this->database->fetch("SELECT v.* FROM videos v ORDER BY RAND() LIMIT 1");
    }

    /**
     * Returns an array for the community page.
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getData(): array
    {
        $randomSubmissionAuthor = new User($this->database, $this->random_submission["author"]);

        $randomSubmissionData = [
            "id" => $this->random_submission["video_id"],
            "title" => $this->random_submission["title"],
            "description" => $this->random_submission["description"],
            "published" => $this->random_submission["time"],
            "type" => $this->random_submission["post_type"],
            "file" => MiscFunctions::getSubmissionFile($this->random_submission),
            "author" => [
                "id" => $this->random_submission["author"],
                "info" => $randomSubmissionAuthor->getUserArray(),
            ],
        ];

        $suggestionsData = [];
        foreach ($this->suggestions as $suggestion) {
            $userData = new User($this->database, $suggestion["author"]);
            $suggestionsData[] =
                [
                    "id" => $suggestion["id"],
                    "title" => $suggestion["title"],
                    "description" => $suggestion["description"],
                    "posted" => $suggestion["time"],
                    "author" => [
                        "id" => $suggestion["author"],
                        "info" => $userData->getUserArray(),
                    ],
                ];
        }

        return [
            "random_submission" => $randomSubmissionData,
            "suggestions" => $suggestionsData,
        ];
    }
}