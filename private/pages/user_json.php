<?php

namespace OpenSB;

global $orange, $domain, $path;

$db = $orange->getDatabase();

$data = $db->fetch("SELECT u.* FROM users u WHERE u.name = ?", [$path[1]]);

$output = [
    "@context" => [
        "https://www.w3.org/ns/activitystreams",
    ],
    "type" => "Person",
    "id" => "https://{$domain}/{$data["name"]}",
    //"following" => "https://kenzoishii.example.com/following.json",
    //"followers" => "https://kenzoishii.example.com/followers.json",
    //"liked" => "https://kenzoishii.example.com/liked.json",
    //"inbox" => "https://kenzoishii.example.com/inbox.json",
    //"outbox" => "https://kenzoishii.example.com/feed.json",
    "preferredUsername" => "{$data["name"]}",
    "name" => "{$data["title"]}",
    "summary" => "{$data["about"]}",
    "icon" => [
        "https://{$domain}/dynamic/pfp/{$data["name"]}.png"
    ]
];

header("Content-Type: application/activity+json");
echo json_encode($output);