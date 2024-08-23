<?php

namespace OpenSB;

global $auth, $twig, $database, $orange;

use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;

if (!$auth->isUserAdmin()) {
    UnorganizedFunctions::Notification("You do not have permission to access this page.", "/");
}

if (!$auth->hasUserAuthenticatedAsAnAdmin()) {
    UnorganizedFunctions::Notification("Please login with your admin password.", "/admin/login");
}

if ($orange->getLocalOptions()["skin"] != "biscuit" && $orange->getLocalOptions()["skin"] != "charla") {
    UnorganizedFunctions::Notification("Please change your skin to Biscuit.", "/theme");
}

$usersData = [];

$usersDataQuery = $database->fetchArray(
    $database->query(
        "SELECT u.id, u.about, u.title, u.ip, u.powerlevel,
       (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num, 
       (SELECT COUNT(*) FROM journals WHERE author = u.id) AS j_num,
       (SELECT COUNT(*) FROM bans WHERE userid = u.id) AS is_banned
        FROM users u"));

$countedIps = array_count_values(array_column($usersDataQuery, 'ip'));

foreach ($usersDataQuery as $user) {
    $class = null;

    // NOTE: 999.999.999.999 is the default value of IPs in the DB.
    // accounts may still have "999.999.999.999" if they haven't been logged into
    // before like, late-2023? i don't know, it's kinda fucky. -chaziz 6/29/2024
    if ($countedIps[$user["ip"]] > 1 && $user["ip"] != "999.999.999.999") {
        $class = "unbanned-other-unbanned";
    }

    if ($user["powerlevel"] > 1) {
        $class = "staff";
    }

    if ($user["is_banned"]) {
        $class = "banned";
    }

    $userData = new UserData($database, $user["id"]);
    $usersData[] =
        [
            "id" => $user["id"],
            "info" => $userData->getUserArray(),
            "submissions" => $user["s_num"],
            "journals" => $user["j_num"],
            "banned" => $user["is_banned"],
            "about" => $user["about"],
            "class" => $class,
        ];
}

echo $twig->render("admin_users.twig", ["users" => $usersData]);