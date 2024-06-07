<?php

namespace OpenSB;

global $orange, $domain, $path, $storage;

$db = $orange->getDatabase();

if (str_contains($path[2], "@" . $domain)) {
    $name = explode('@', $path[2])[0];
} elseif (str_contains($path[2], "@")) {
    http_response_code(404);
    die();
} else {
    $name = $path[2];
}

$data = $db->fetch("SELECT name, title, about FROM users WHERE name = ?", [$name]);

if (!$data)
{
    http_response_code(404);
    die();
}

$pfpLocation = '/dynamic/pfp/' . $data["name"] . '.png';

$output = [
    "@context" => [
        "https://www.w3.org/ns/activitystreams",
    ],
    "type" => "Person",
    "id" => "https://{$domain}/user/{$data["name"]}",
    "following" => "https://{$domain}/user/{$data["name"]}/following",
    "followers" => "https://{$domain}/user/{$data["name"]}/followers",
    "liked" => "https://{$domain}/user/{$data["name"]}/liked",
    "inbox" => "https://{$domain}/user/{$data["name"]}/inbox",
    "outbox" => "https://{$domain}/user/{$data["name"]}/feed",
    "preferredUsername" => "{$data["name"]}",
    "name" => "{$data["title"]}",
    "summary" => "{$data["about"]}",
];

if ($storage->fileExists('..' . $pfpLocation)) {
    $output["icon"] = [
        "type" => "Image",
        "url" => "https://{$domain}/dynamic/pfp/{$data["name"]}.png"
    ];
}

header("Content-Type: application/activity+json");
echo json_encode($output);