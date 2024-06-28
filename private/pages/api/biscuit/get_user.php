<?php

// primarily intended for the getSbAccounts() js function, maybe, i don't know.
// -chaziz 6/28/2024

namespace OpenSB;

global $database, $path, $storage;

$apiOutput = [
    "error" => "The user " . $path[4] . " does not exist."
];

header('Content-Type: application/json');

$user_id = $path[4] ?? null;

$data = $database->fetch("SELECT name FROM users u WHERE u.id = ?", [$user_id]);
$is_banned = $database->fetch("SELECT * FROM bans WHERE userid = ?", [$user_id]);

if ($is_banned) {
    $apiOutput = [
        "error" => "The user " . $path[4] . " is banned."
    ];
}

if ($data) {
    $profile_picture = '/dynamic/pfp/' . $user_id . '.png';

    if ($storage->fileExists('..' . $profile_picture)) {
        $pfp = $profile_picture;
    } else {
        $pfp = "/assets/profiledef.png";
    }

    $apiOutput = [
        "id" => $user_id,
        "username" => $data["name"],
        "profile_picture" => $pfp,
    ];
}

echo json_encode($apiOutput);