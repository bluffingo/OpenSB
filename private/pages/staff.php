<?php

namespace OpenSB;

global $twig, $database, $orange;

use OpenSB\class\Core\UserData;
use OpenSB\class\Core\Utilities;

if ($orange->getLocalOptionsClass()->getOptions()["skin"] != "charla") {
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