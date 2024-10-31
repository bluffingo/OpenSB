<?php

namespace OpenSB;

global $twig, $database, $orange;

use OpenSB\class\Core\UserData;
use OpenSB\class\Core\UserRoleEnum;
use OpenSB\class\Core\Utilities;

if ($orange->getLocalOptions()["skin"] != "biscuit" && $orange->getLocalOptions()["skin"] != "charla") {
    Utilities::bannerNotification("Please change your skin to Biscuit.", "/theme");
}

$staffQueryData = $database->fetchArray(
    $database->query(
        "SELECT u.id, u.powerlevel
        FROM users u 
        WHERE u.powerlevel >= ?", [UserRoleEnum::Moderator->value]));

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