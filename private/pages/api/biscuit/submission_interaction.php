<?php

namespace OpenSB;

global $auth, $database;

header('Content-Type: application/json');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "This request is invalid."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "You have been banned."
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
            // favorites are still unimplemented FUCK (READD FAVORITES FOR OPENSB 1.3 BETA 3) -chaziz 10/31/2024
            'favorite' => [
                "favorited" => true,
                "number" => rand(0, 47101), // placeholder code (which is still placeholder even a year later since favorites were never implemented WHOOPS)
            ],
            'rate' => [
                rate($post_data['number'], $post_data['submission']),
            ],
            default => [
                "error" => "This interaction type is invalid or has not yet been implemented."
            ],
        };
    }
}

echo json_encode($apiOutput);
