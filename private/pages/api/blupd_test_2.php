<?php
// qobo's down until bitqobo rewrite. squarebracket's still months away. so I'll just dump this here.
// it's not the first time I've used opensb's repository for prototyping other things in it.
namespace OpenSB;

header('Content-Type: application/json');

$application = [
    "firefox" => [
        "id" => "firefox",
        "name" => "Mozilla Firefox",
        "author" => "Mozilla"
    ],
    "msnmsg" => [
        "id" => "msnmsg",
        "name" => "Windows Live Messenger",
        "author" => "Microsoft"
    ],
    "winamp" => [
        "id" => "winamp",
        "name" => "Winamp",
        "author" => "Radionomy"
    ],
    "yahoomsg" => [
        "id" => "yahoomsg",
        "name" => "Yahoo! Messenger",
        "author" => "Yahoo!"
    ],
];

echo json_encode($application);