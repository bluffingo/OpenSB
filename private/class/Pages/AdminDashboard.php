<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\OrangeException;
use Orange\Database;

/**
 * Backend code for the admin dashboard.
 *
 * @since Orange 1.0
 */
class AdminDashboard
{
    private \Orange\Database $database;
    private array $data;

    public function __construct(\Orange\Orange $orange, $POST, $GET)
    {
        global $auth, $isQoboTV;
        // Honest question: Why the fuck are we using globals for getting shit from config.php? This seems poorly
        // designed. -Bluffingo 12/11/2023

        $this->database = $orange->getDatabase();
        if (!$auth->isUserAdmin()) {
            $orange->Notification("You do not have permission to access this page", "/");
        }

        // If $isQoboTV is on, just go with January 31st 2021, otherwise and try and guess the instance's creation date
        // from the earliest account registration date. In normal conditions, the earliest account is older than the
        // earliest submission on the instance. However, the "squareBracket" account on Qobo has an altered registration
        // date of November 10th 2020, which was supposed to be a reference to when the site "began development".
        // Unfortunately, it is unknown as to when that account was registered prior to the alteration, as the earliest
        // archives of this account's profile from June 2021 are from after the account data was altered.
        // https://web.archive.org/web/20210625203937/https://squarebracket.veselcraft.ru/user.php?name=squareBracket
        // Bluffingo -12/11/2023

        if ($isQoboTV) {
            $date = mktime(0, 0, 0, 1, 31, 2021);
        } else {
            $date = $this->database->fetch("SELECT u.joined FROM users u ORDER BY u.joined ASC")["joined"];
        }

        // Admin actions
        if(isset($POST["action"])) {
            if ($POST["action"] == "ban_user") {
                // Don't ban non-existent users.
                if (!$this->database->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])) {
                    $orange->Notification("This user does not exist.", "/admin.php");
                }
                // Don't ban mods/admins.
                if ($this->database->fetch("SELECT u.powerlevel FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["powerlevel"] != 1) {
                    $orange->Notification("This user cannot be banned.", "/admin.php");
                }
                // Check if user is already banned, if not, then ban.
                $id = $this->database->fetch("SELECT u.id FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["id"];
                if ($this->database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$id])) {
                    $orange->Notification("This user is already banned.", "/admin.php");
                } else {
                    $this->database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                        [$id, $POST["reason"], time()]);
                    $orange->Notification("Banned user!", "/admin.php");
                }
            }
        } else if (isset($GET["action"])) {
            if ($GET["action"] == "unban_user") {
                if ($this->database->query("DELETE FROM bans WHERE userid = ?", [$GET["user"]])) {
                    $orange->Notification("Unbanned user!", "/admin.php");
                }
            }
        }

        // Total number of things
        $thingsToCount = ['comments', 'channel_comments', 'users', 'videos', 'views', 'favorites', 'bans', 'journals'];
        $query = "SELECT ";
        foreach ($thingsToCount as $thing) {
            if ($query != "SELECT ") $query .= ", ";
            $query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
        }

        // Get the bans
        $bans = $this->database->fetchArray($this->database->query("SELECT * FROM bans"));

        $bannedUserData = [];
        foreach ($bans as $ban) {
            $banned_user = $this->database->fetch("SELECT u.* FROM users u WHERE u.id = ?", [$ban["userid"]]);
            $banned_user["ban_reason"] = $ban["reason"];
            $banned_user["ban_time"] = $ban["time"];
            $bannedUserData[] = $banned_user;
        }

        $this->data = [
            "numbers" => $this->database->fetch($query),
            "system" => [
                "uname" => php_uname(),
            ],
            "graph_data" => [
                "users" => $this->getUserGraph(),
                "submissions" => $this->getVideoGraph(),
                "comments" => $this->getCommentGraph(),
                "shouts" => $this->getShoutsGraph(),
                "journals" => $this->getJournalGraph(),
            ],
            "bans" => $bannedUserData,
            "time" => [
                "formatted_date" => date("F j, Y", $date),
                "relative_days" => round((time() - $date) / 60 / 60 / 24), // we want the total number of days,
                // not a rounded approximation, so relative time isn't gonna work.
            ],
        ];
    }

    public function getData()
    {
        return $this->data;
    }

    private function getVideoGraph(): array
    {
        $this->database->query("SET @runningTotal = 0;");
        $videoData = $this->database->query("
SELECT 
    time,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(time) AS time,
    COUNT(*) AS num_interactions
FROM videos AS e
GROUP BY DATE(FROM_UNIXTIME(e.time))) totals
ORDER BY time;");
        $videos = $this->database->fetchArray($videoData);
        return $videos;
    }

    private function getCommentGraph(): array
    {
        $this->database->query("SET @runningTotal = 0;");
        $videoData = $this->database->query("
SELECT 
    date,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(date) AS date,
    COUNT(*) AS num_interactions
FROM comments AS e
GROUP BY DATE(FROM_UNIXTIME(e.date))) totals
ORDER BY date;");
        $videos = $this->database->fetchArray($videoData);
        return $videos;
    }

    private function getShoutsGraph(): array
    {
        $this->database->query("SET @runningTotal = 0;");
        $videoData = $this->database->query("
SELECT 
    date,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(date) AS date,
    COUNT(*) AS num_interactions
FROM channel_comments AS e
GROUP BY DATE(FROM_UNIXTIME(e.date))) totals
ORDER BY date;");
        $videos = $this->database->fetchArray($videoData);
        return $videos;
    }

    private function getUserGraph(): array
    {
        $this->database->query("SET @runningTotal = 0;");
        $userData = $this->database->query("
SELECT 
    joined,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(joined) AS joined,
    COUNT(*) AS num_interactions
FROM users AS e
GROUP BY DATE(FROM_UNIXTIME(e.joined))) totals
ORDER BY joined;");
        $users = $this->database->fetchArray($userData);
        return $users;
    }

    private function getJournalGraph(): array
    {
        $this->database->query("SET @runningTotal = 0;");
        $videoData = $this->database->query("
SELECT 
    date,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(date) AS date,
    COUNT(*) AS num_interactions
FROM journals AS e
GROUP BY DATE(FROM_UNIXTIME(e.date))) totals
ORDER BY date;");
        $videos = $this->database->fetchArray($videoData);
        return $videos;
    }
}