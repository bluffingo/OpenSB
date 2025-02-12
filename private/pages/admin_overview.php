<?php

namespace OpenSB;

global $auth, $isChazizSB, $twig, $database, $orange;

use OpenSB\class\Core\Utilities;

if (!$auth->isUserAdmin()) {
    Utilities::bannerNotification("You do not have permission to access this page.", "/");
}

if (!$auth->hasUserAuthenticatedAsAnAdmin()) {
    Utilities::bannerNotification("Please login with your admin password.", "/admin/login");
}

if ($orange->getLocalOptionsClass()->getOptions()["skin"] != "biscuit" && $orange->getLocalOptionsClass()->getOptions()["skin"] != "charla") {
    Utilities::bannerNotification("Please change your skin to Biscuit.", "/theme");
}

/**
 * Based on the implementation in principia-web. Originally, this was 5 slightly different duplicated functions.
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
            FROM upload_comments
            GROUP BY DATE(FROM_UNIXTIME(date)))
            UNION ALL
            (SELECT FROM_UNIXTIME(date) AS date, COUNT(*) AS num_interactions
            FROM user_profile_comments
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
        FROM upload_views
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
    if ($_POST["action"] == "generate_invite_key") {
        $random = strtoupper("SB" . Utilities::generateRandomString(16));

        $database->query("INSERT INTO invite_keys (invite_key, generated_by, generated_time) VALUES (?,?,?)",
            [$random, $auth->getUserID(), time()]);

        Utilities::bannerNotification("Generated key! ($random)", "/admin.php", "success");
    }
}

// Total number of things
$thingsToCount = ['upload_comments', 'user_profile_comments', 'users', 'uploads', 'upload_views', 'user_favorites', 'user_bans', 'journals'];
$query = "SELECT ";
foreach ($thingsToCount as $thing) {
    if ($query != "SELECT ") $query .= ", ";
    $query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
}

$numbersOfThingsArray = $database->fetch($query);

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
    "numbers" => $numbersOfThingsArray,
    "system" => [
        "uname" => php_uname(),
    ],
    "graph_data" => [
        "users" => makeRunningTotalGraph($database, 'users', 'joined'),
        "submissions" => makeRunningTotalGraph($database, 'uploads', 'time'),
        "comments" => makeRunningTotalGraphFromMultipleCommentTables($database),
        "journals" => makeRunningTotalGraph($database, 'journals', 'date'),
        "views" => countViews($database),
    ],
    "invites" => $inviteKeyData,
    "time" => [
        "formatted_date" => date("F j, Y", $date),
        "relative_days" => round((time() - $date) / 60 / 60 / 24), // we want the total number of days,
        // not a rounded approximation, so relative time isn't gonna work.
    ],
];

echo $twig->render('admin_overview.twig', [
    'data' => $data
]);