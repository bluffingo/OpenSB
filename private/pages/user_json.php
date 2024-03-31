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
    "following" => "https://{$domain}/{$data["name"]}/following",
    "followers" => "https://{$domain}/{$data["name"]}/followers",
    "liked" => "https://{$domain}/{$data["name"]}/liked",
    "inbox" => "https://{$domain}/{$data["name"]}/inbox",
    "outbox" => "https://{$domain}/{$data["name"]}/feed",
    "preferredUsername" => "{$data["name"]}",
    "name" => "{$data["title"]}",
    "summary" => "{$data["about"]}",
    "icon" => [
        "type" => "Image",
        "url" => "https://{$domain}/dynamic/pfp/{$data["name"]}.png"
    ]
];

header("Content-Type: application/activity+json");
echo json_encode($output);