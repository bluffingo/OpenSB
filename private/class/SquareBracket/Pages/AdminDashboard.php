<?php

namespace SquareBracket\Pages;

use SquareBracket\UnorganizedFunctions;

/**
 * Backend code for the admin dashboard.
 *
 * @since SquareBracket 1.0
 */
class AdminDashboard
{
    private \Core\Database $database;
    private array $data;

    public function __construct(\SquareBracket\SquareBracket $orange, $POST, $GET)
    {
        global $auth, $isBluffingoSB;
        // Honest question: Why the fuck are we using globals for getting shit from config.php? This seems poorly
        // designed. -Bluffingo 12/11/2023

        $this->database = $orange->getDatabase();
        if (!$auth->isUserAdmin()) {
            UnorganizedFunctions::Notification("You do not have permission to access this page", "/");
        }

        // If $isBluffingoSB is on, just go with January 31st 2021, otherwise and try and guess the instance's creation date
        // from the earliest account registration date. In normal conditions, the earliest account is older than the
        // earliest submission on the instance. However, the "squareBracket" account on Qobo has an altered registration
        // date of November 10th 2020, which was supposed to be a reference to when the site "began development".
        // Unfortunately, it is unknown as to when that account was registered prior to the alteration, as the earliest
        // archives of this account's profile from June 2021 are from after the account data was altered.
        // https://web.archive.org/web/20210625203937/https://squarebracket.veselcraft.ru/user.php?name=squareBracket
        // Bluffingo -12/11/2023

        if ($isBluffingoSB) {
            $date = mktime(0, 0, 0, 1, 31, 2021);
        } else {
            $date = $this->database->fetch("SELECT u.joined FROM users u ORDER BY u.joined ASC")["joined"];
        }

        // Admin actions
        if(isset($POST["action"])) {
            if ($POST["action"] == "ban_user") {
                // Don't ban non-existent users.
                if (!$this->database->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])) {
                    UnorganizedFunctions::Notification("This user does not exist.", "/admin.php");
                }
                // Don't ban mods/admins.
                if ($this->database->fetch("SELECT u.powerlevel FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["powerlevel"] != 1) {
                    UnorganizedFunctions::Notification("This user cannot be banned.", "/admin.php");
                }
                // Check if user is already banned, if not, then ban.
                $id = $this->database->fetch("SELECT u.id FROM users u WHERE u.name = ?", [$POST["user_to_ban"]])["id"];
                if ($this->database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$id])) {
                    UnorganizedFunctions::Notification("This user is already banned.", "/admin.php");
                } else {
                    $this->database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                        [$id, $POST["reason"], time()]);
                    UnorganizedFunctions::Notification("Banned user!", "/admin.php");
                }
            }
        } else if (isset($GET["action"])) {
            if ($GET["action"] == "unban_user") {
                if ($this->database->query("DELETE FROM bans WHERE userid = ?", [$GET["user"]])) {
                    UnorganizedFunctions::Notification("Unbanned user!", "/admin.php");
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
                "users" => $this->makeRunningTotalGraph('users', 'joined'),
                "submissions" => $this->makeRunningTotalGraph('videos', 'time'),
                "comments" => $this->makeRunningTotalGraph('comments', 'date'),
                "shouts" => $this->makeRunningTotalGraph('channel_comments', 'date'),
                "journals" => $this->makeRunningTotalGraph('journals', 'date'),
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

    /**
     * Based on the implementation in principia-web. Originally, this was 5 slightly different duplicated functions.
     *
     * @since SquareBracket 1.1
     */
    private function makeRunningTotalGraph($table, $orderfield): array
    {
        $this->database->query("SET @runningTotal = 0;");
        return $this->database->fetchArray($this->database->query(
            "SELECT $orderfield, num_interactions,
			@runningTotal := @runningTotal + totals.num_interactions AS runningTotal
		FROM
			(SELECT FROM_UNIXTIME($orderfield) AS $orderfield, COUNT(*) AS num_interactions
				FROM $table AS e
				GROUP BY DATE(FROM_UNIXTIME(e.$orderfield))) totals
		ORDER BY $orderfield"));
    }
}