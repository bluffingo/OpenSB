<?php

namespace Orange\Pages;

use Orange\Utilities;
use Orange\NoticeType;
use Orange\User;
use Orange\OrangeException;
use Orange\Database;

/**
 * Backend code for the index page.
 *
 * @since Orange 1.0
 */
class Index
{
    private \Orange\Database $database;
    private array $submissions;
    private array $submissions_recent;
    private array $news_recent;

    public function __construct(\Orange\Orange $orange)
    {
        $whereRatings = Utilities::whereRatings();

        $this->database = $orange->getDatabase();
        $this->submissions = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY RAND() LIMIT 24"));
        $this->submissions_recent = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND $whereRatings ORDER BY v.time DESC LIMIT 24"));
        $this->news_recent = $this->database->fetchArray($this->database->query("SELECT j.* FROM journals j WHERE j.is_site_news = 1 ORDER BY j.date DESC LIMIT 3"));
    }

    /**
     * Returns an array containing a random list of submissions for the openSB frontend.
     *
     * @since Orange 1.0
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            "submissions" => Utilities::makeSubmissionArray($this->database, $this->submissions),
            "submissions_new" => Utilities::makeSubmissionArray($this->database, $this->submissions_recent),
            "news_recent" => $this->news_recent,
        ];
    }
}