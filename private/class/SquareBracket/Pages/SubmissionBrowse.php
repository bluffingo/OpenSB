<?php

namespace SquareBracket\Pages;

use SquareBracket\Utilities;

/**
 * Backend code for the submission browsing page.
 *
 * @since SquareBracket 1.0
 */
class SubmissionBrowse
{
    private \SquareBracket\Database $database;
    private array $submissions;
    private $order;
    private $limit;
    private $submission_count;

    public function __construct(\SquareBracket\SquareBracket $orange, $type, $page)
    {
        global $auth;
        $this->order = $this->getOrderFromType($type);
        $this->limit = sprintf("LIMIT %s,%s", (($page - 1) * 20), 20);

        $whereRatings = Utilities::whereRatings();

        $this->database = $orange->getDatabase();
        $this->submissions = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY $this->order DESC $this->limit"));
        $this->submission_count = $this->database->result("SELECT COUNT(*) FROM videos");
    }

    private function getOrderFromType($type): string
    {
        switch ($type) {
            case 'recent':
                $order = "v.time";
                break;
            case 'popular':
                $order = "views";
                break;
            case 'discussed':
                $order = "comments";
                break;
            case 'favorited':
                $order = "favorites";
                break;
            case 'random':
                $order = "RAND()";
                break;
            default:
                $order = "v.time";
                break;
        }
        return $order;
    }

    public function getData(): array
    {
        return [
            "submissions" => Utilities::makeSubmissionArray($this->database, $this->submissions),
            "count" => $this->submission_count,
        ];
    }
}