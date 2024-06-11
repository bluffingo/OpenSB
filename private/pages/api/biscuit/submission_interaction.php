<?php

namespace OpenSB;

global $auth, $database;

header('Content-Type: application/json');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "User is banned!!!"
    ];
}

function rate($number, $submission): array
{
    global $database, $auth;

    // shouldn't this update instead?
    if ($database->result("SELECT COUNT(rating) FROM rating WHERE video=? AND user=?", [$submission, $auth->getUserID()]))
    {
        $database->query("DELETE FROM rating WHERE video=? AND user=?", [$submission, $auth->getUserID()]);
    }
    $database->query("INSERT INTO rating (video, user, rating) VALUES (?,?,?)", [$submission, $auth->getUserID(), $number]);
    return [ "rated" => true ];
}

if (isset($post_data['submission'])) {
    if (isset($post_data['action'])) {
        $apiOutput = match ($post_data['action']) {
            'favorite' => [
                "favorited" => true,
                "number" => rand(0, 47101), // placeholder code (which is still placeholder even a year later since favorites were never implemented WHOOPS)
            ],
            'rate' => [
                rate($post_data['number'], $post_data['submission']),
            ],
            default => [
                "error" => "Invalid interaction type, or NYI"
            ],
        };
    }
}

echo json_encode($apiOutput);
