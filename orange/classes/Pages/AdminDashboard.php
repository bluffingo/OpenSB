<?php

namespace Orange\Pages;

use Orange\MiscFunctions;
use Orange\User;
use Orange\OrangeException;
use Orange\Database;

/**
 * Backend code for the admin dashboard.
 *
 * @since 0.1.0
 */
class AdminDashboard
{
    private \Orange\Database $database;
    private array $data;

    public function __construct(\Orange\Orange $betty, $POST, $GET)
    {
        global $auth;

        $this->database = $betty->getBettyDatabase();
        if (!$auth->isUserAdmin()) {
            $betty->Notification("You do not have permission to access this page", "/");
        }

        if(isset($POST["action"])) {
            if ($POST["action"] == "ban_user") {
                // Don't ban non-existent users.
                if (!$this->database->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])) {
                    $betty->Notification("This user does not exist.", "/admin.php");
                }
                // Don't ban mods/admins.
                if ($this->database->fetch("SELECT u.powerlevel FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["powerlevel"] != 1) {
                    $betty->Notification("This user cannot be banned.", "/admin.php");
                }
                // Check if user is already banned, if not, then ban.
                $id = $this->database->fetch("SELECT u.id FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["id"];
                if ($this->database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$id])) {
                    $betty->Notification("This user is already banned.", "/admin.php");
                } else {
                    $this->database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                        [$id, $POST["reason"], time()]);
                    $betty->Notification("Banned user!", "/admin.php");
                }
            }
        } else if (isset($GET["action"])) {
            if ($GET["action"] == "unban_user") {
                if ($this->database->query("DELETE FROM bans WHERE userid = ?", [$GET["user"]])) {
                    $betty->Notification("Unbanned user!", "/admin.php");
                }
            }
        }

        $thingsToCount = ['comments', 'channel_comments', 'users', 'videos', 'views', 'favorites', 'bans', 'journals'];
        $query = "SELECT ";
        foreach ($thingsToCount as $thing) {
            if ($query != "SELECT ") $query .= ", ";
            $query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
        }

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