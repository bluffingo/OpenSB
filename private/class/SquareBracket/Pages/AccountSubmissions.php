<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * why the FUCK did i design the code to be like this
 *
 * @since SquareBracket 1.1
 */
class AccountSubmissions
{
    private \Core\Database $database;
    private array $submissions;
    private $order;
    private $limit;
    private $submission_count;

    public function __construct(\SquareBracket\SquareBracket $orange, $type, $page)
    {
        global $auth;

        if (!$auth->isUserLoggedIn())
        {
            UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
        }

        $this->limit = sprintf("LIMIT %s,%s", (($page - 1) * 20), 20);

        $this->database = $orange->getDatabase();
        $this->submissions = $this->database->fetchArray($this->database->query("SELECT v.* FROM videos v WHERE v.video_id NOT IN (SELECT submission FROM takedowns) AND v.author = ? ORDER BY v.id DESC $this->limit", [$auth->getUserID()]));
        $this->submission_count = $this->database->result("SELECT COUNT(*) FROM videos where videos.author = ?", [$auth->getUserID()]);
    }

    public function getData(): array
    {
        return [
            "submissions" => UnorganizedFunctions::makeSubmissionArray($this->database, $this->submissions),
            "count" => $this->submission_count,
        ];
    }
}