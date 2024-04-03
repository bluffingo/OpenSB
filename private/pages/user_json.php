<?php

namespace OpenSB;

global $orange, $domain, $path;

$db = $orange->getDatabase();

if (str_contains($path[2], "@" . $domain)) {
    $name = explode('@', $path[2])[0];
} elseif (str_contains($path[2], "@")) {
    http_response_code(404);
    die();
} else {
    $name = $path[2];
}

$data = $db->fetch("SELECT u.* FROM users u WHERE u.name = ?", [$name]);

if (!$data)
{
    http_response_code(404);
    die();
}

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