<?php

namespace OpenSB;

global $auth, $twig, $database, $orange;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page", "/");
}

if ($orange->getLocalOptions()["skin"] != "biscuit") {
    UnorganizedFunctions::Notification("Please change your skin to Biscuit.", "/theme");
}

$usersData = [];

$usersDataQuery = $database->fetchArray(
    $database->query(
        "SELECT u.id, u.about, u.title,
       (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num, 
       (SELECT COUNT(*) FROM journals WHERE author = u.id) AS j_num,
       (SELECT COUNT(*) FROM bans WHERE userid = u.id) AS is_banned
        FROM users u 
        ORDER BY u.lastview DESC"));

foreach ($usersDataQuery as $user) {
    $userData = new UserData($database, $user["id"]);
    $usersData[] =
        [
            "id" => $user["id"],
            "info" => $userData->getUserArray(),
            "submissions" => $user["s_num"],
            "journals" => $user["j_num"],
            "is_banned" => $user["is_banned"],
            "about" => $user["about"],
        ];
}

echo $twig->render("admin_users.twig", ["users" => $usersData]);