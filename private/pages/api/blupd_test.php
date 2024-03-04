<?php
// qobo's down until bitqobo rewrite. squarebracket's still months away. so I'll just dump this here.
// it's not the first time I've used opensb's repository for prototyping other things in it.
namespace OpenSB;

header('Content-Type: application/json');

$application = [
    "firefox" => [
        "id" => "firefox",
        "name" => "Mozilla Firefox",
        "version" => "3.6.28",
        "released" => "2012-03-07", // ISO 8601
        "download" => "https://ftp.mozilla.org/pub/firefox/releases/3.6.28/win32/en-US/Firefox%20Setup%203.6.28.exe",
    ],
    "test" => [
        "id" => "test",
        "name" => "Testing 2nd Application",
        "version" => "1.2.3a-helloworld",
        "released" => "2024-03-03", // ISO 8601
        "download" => "https://ftp.mozilla.org/pub/firefox/releases/3.6.28/win32/en-US/Firefox%20Setup%203.6.28.exe",
    ],
];

echo json_encode($application);