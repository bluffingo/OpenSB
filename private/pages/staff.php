<?php

namespace OpenSB;

global $twig, $database, $orange;

use SquareBracket\UserData;
use SquareBracket\Utilities;

if ($orange->getLocalOptions()["skin"] != "charla") {
    Utilities::bannerNotification("Please change your skin to Charla.", "/theme");
}

$staffQueryData = $database->fetchArray(
    $database->query(
        "SELECT u.id, u.powerlevel
        FROM users u 
        WHERE u.powerlevel >= '3'"));

$usersData = [];
foreach ($staffQueryData as $user)
{
    $userData = new UserData($database, $user["id"]);
    $usersData[] =
        [
            "id" => $user["id"],
            "info" => $userData->getUserArray(),
            "level" => $user["powerlevel"],
        ];
}

echo $twig->render('staff.twig', [
    'staff' => $usersData,
]);