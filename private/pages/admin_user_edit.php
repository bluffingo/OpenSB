<?php

namespace OpenSB;

global $auth, $twig, $database, $orange, $path;

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

$username = $path[3] ?? null;

$user = $database->fetch("SELECT * FROM users u WHERE u.name = ?", [$username]);

if ($user["ip"] != "999.999.999.999") {
    $users_with_matching_ips = $database->fetchArray($database->query("SELECT u.name, u.title FROM users u WHERE u.ip = ? AND id != ?",
        [$user["ip"], $user["id"]]));
} else {
    $users_with_matching_ips = [];
}

$old_username_data = $database->fetchArray($database->query("SELECT * FROM user_old_names WHERE user = ?", [$user["id"]]));

$notes = $database->fetchArray($database->query("SELECT * FROM user_staff_notes WHERE user = ?", [$user["id"]]));

$notes_proper = [];

foreach ($notes as $note) {
    $userData = new UserData($database, $note["author"]);
    $notes_proper[] = [
        "content" => $note["note"],
        "time" => $note["time"],
        "author" => [
            "id" => $note["author"],
            "info" => $userData->getUserArray(),
        ],
    ];
}

echo $twig->render('admin_user_edit.twig', [
    'user' => $user,
    'users_with_matching_ips' => $users_with_matching_ips,
    'notes' => $notes_proper,
    'old_names' => $old_username_data
]);