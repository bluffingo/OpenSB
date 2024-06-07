<?php
// ported from principia-web by chaziz -4/20/2023
namespace OpenSB;

// TODO: do not include fake "users" generated from activitypub profiles. -chaziz 6/7/2024

global $twig, $database;

use SquareBracket\UserData;

$queryData = $database->fetchArray($database->query("SELECT u.id, u.about, u.title, (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num, (SELECT COUNT(*) FROM journals WHERE author = u.id) AS j_num FROM users u ORDER BY u.lastview DESC"));

$usersData = [];
foreach ($queryData as $user)
{
    $user_banned = $database->fetch("SELECT * FROM bans WHERE userid = ?", [$user["id"]]);
    if (!$user_banned) {
        $userData = new UserData($database, $user["id"]);
        $usersData[] =
            [
                "id" => $user["id"],
                "info" => $userData->getUserArray(),
                "submissions" => $user["s_num"],
                "journals" => $user["j_num"],
                "about" => $user["about"],
            ];
    }
}

$data = [
    "users" => $usersData,
    "total" => count($usersData),
];

echo $twig->render('users.twig', [
	'users' => $data,
]);
