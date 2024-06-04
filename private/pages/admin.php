<?php

namespace OpenSB;

global $auth, $isChazizSB, $twig, $database;

use SquareBracket\UnorganizedFunctions;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page", "/");
}

/**
 * Based on the implementation in principia-web. Originally, this was 5 slightly different duplicated functions.
 *
 * @since SquareBracket 1.1
 */
function makeRunningTotalGraph($database, $table, $orderfield): array
{
    $database->query("SET @runningTotal = 0;");
    return $database->fetchArray($database->query(
        "SELECT $orderfield, num_interactions,
			@runningTotal := @runningTotal + totals.num_interactions AS runningTotal
		FROM
			(SELECT FROM_UNIXTIME($orderfield) AS $orderfield, COUNT(*) AS num_interactions
				FROM $table AS e
				GROUP BY DATE(FROM_UNIXTIME(e.$orderfield))) totals
		ORDER BY $orderfield"));
}

function makeRunningTotalGraphFromMultipleCommentTables($database): array
{
    $database->query("SET @runningTotal = 0;");
    return $database->fetchArray($database->query(
        "SELECT date, num_interactions,
            @runningTotal := @runningTotal + num_interactions AS runningTotal
        FROM (
            (SELECT FROM_UNIXTIME(date) AS date, COUNT(*) AS num_interactions
            FROM comments
            GROUP BY DATE(FROM_UNIXTIME(date)))
            UNION ALL
            (SELECT FROM_UNIXTIME(date) AS date, COUNT(*) AS num_interactions
            FROM channel_comments
            GROUP BY DATE(FROM_UNIXTIME(date)))
            UNION ALL
            (SELECT FROM_UNIXTIME(date) AS date, COUNT(*) AS num_interactions
            FROM journal_comments
            GROUP BY DATE(FROM_UNIXTIME(date)))
        ) AS combined_data
        ORDER BY date"
    ));
}

function countViews($database): array
{
    return $database->fetchArray($database->query(
        "SELECT 
            DATE(FROM_UNIXTIME(timestamp)) AS date, 
            SUM(CASE WHEN type = 'user' THEN 1 ELSE 0 END) AS user_views,
            SUM(CASE WHEN type = 'guest' THEN 1 ELSE 0 END) AS guest_views
        FROM views
        GROUP BY DATE(FROM_UNIXTIME(timestamp))
        ORDER BY DATE(FROM_UNIXTIME(timestamp))"
    ));
}

// squarebracket's production db has random references to dates prior to january 31st 2021, but the site
// did launch back there, so just hardcode $date to that date. -chaziz 6/4/2024 (replaces rambling)
if ($isChazizSB) {
    $date = mktime(0, 0, 0, 1, 31, 2021);
} else {
    $date = $database->fetch("SELECT u.joined FROM users u ORDER BY u.joined ASC")["joined"];
}

// Admin actions
if(isset($_POST["action"])) {
    if ($_POST["action"] == "ban_user") {
        // Don't ban non-existent users.
        if (!$database->fetch("SELECT u.name FROM users u WHERE u.name = ?", [$_POST["user_to_ban"]])) {
            UnorganizedFunctions::Notification("This user does not exist.", "/admin.php");
        }
        // Don't ban mods/admins.
        if ($database->fetch("SELECT u.powerlevel FROM users u WHERE u.name = ?", [$_POST["user_to_ban"]])["powerlevel"] != 1) {
            UnorganizedFunctions::Notification("This user cannot be banned.", "/admin.php");
        }
        // Check if user is already banned, if not, then ban.
        $id = $database->fetch("SELECT u.id FROM users u WHERE u.name = ?", [$_POST["user_to_ban"]])["id"];
        if ($database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$id])) {
            UnorganizedFunctions::Notification("This user is already banned.", "/admin.php");
        } else {
            $database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                [$id, $_POST["reason"], time()]);
            UnorganizedFunctions::Notification("Banned user!", "/admin.php", "success");
        }
    } elseif ($_POST["action"] == "generate_invite_key") {
        $random = strtoupper("SB" . UnorganizedFunctions::generateRandomizedString(16));

        $database->query("INSERT INTO invite_keys (invite_key, generated_by, generated_time) VALUES (?,?,?)",
            [$random, $auth->getUserID(), time()]);

        UnorganizedFunctions::Notification("Generated key! ($random)", "/admin.php", "success");
    }
} else if (isset($_GET["action"])) {
    if ($_GET["action"] == "unban_user") {
        if ($database->query("DELETE FROM bans WHERE userid = ?", [$_GET["user"]])) {
            UnorganizedFunctions::Notification("Unbanned user!", "/admin.php", "success");
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
$bans = $database->fetchArray($database->query("SELECT * FROM bans"));

$bannedUserData = [];
foreach ($bans as $ban) {
    $banned_user = $database->fetch("SELECT u.* FROM users u WHERE u.id = ?", [$ban["userid"]]);

    // avoids "conversion from false to array" is deprecated error if the banned user no longer exists
    // due to manual db modification although in reality these bans should get automatically revoked
    if (!$banned_user)
    {
        $banned_user = [
            "name" => "Deleted user"
        ];
    }

    $banned_user["ban_reason"] = $ban["reason"];
    $banned_user["ban_time"] = $ban["time"];
    $bannedUserData[] = $banned_user;
}

// Get the invite keys
$inviteKeys = $database->fetchArray($database->query("SELECT * FROM invite_keys"));

$inviteKeyData = [];
foreach ($inviteKeys as $inviteKey) {
    $generatedBy = $database->fetch("SELECT u.name FROM users u WHERE u.id = ?", [$inviteKey["generated_by"]]);
    $claimedBy = $database->fetch("SELECT u.name FROM users u WHERE u.id = ?", [$inviteKey["claimed_by"]]);

    $inviteKey["generated_by"] = $generatedBy;
    $inviteKey["claimed_by"] = $claimedBy;

    $inviteKeyData[] = $inviteKey;
}

$data = [
    "numbers" => $database->fetch($query),
    "system" => [
        "uname" => php_uname(),
    ],
    "graph_data" => [
        "users" => makeRunningTotalGraph($database, 'users', 'joined'),
        "submissions" => makeRunningTotalGraph($database, 'videos', 'time'),
        "comments" => makeRunningTotalGraphFromMultipleCommentTables($database),
        "journals" => makeRunningTotalGraph($database, 'journals', 'date'),
        "views" => countViews($database),
    ],
    "bans" => $bannedUserData,
    "invites" => $inviteKeyData,
    "time" => [
        "formatted_date" => date("F j, Y", $date),
        "relative_days" => round((time() - $date) / 60 / 60 / 24), // we want the total number of days,
        // not a rounded approximation, so relative time isn't gonna work.
    ],
];

echo $twig->render('admin.twig', [
    'data' => $data
]);