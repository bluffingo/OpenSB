<?php

return [
    // Database details. (OpenSB only supports MySQL / MariaDB databases)
    "mysql" => [
        "database" => "sb",
        "username" => "root",
        "password" => "",
        "host" => "127.0.0.1",
    ],
    "captcha" => [
        "enabled" => false,
        "secret" => "",
        "public" => ""
    ],
    // put "PROD" for production, put "DEV" for development
    "mode" => "PROD",
    "site" => "squarebracket",
    "maintenance" => false,
    "cache" => false,
    // Bunny settings, only used if the "site" parameter above is squarebracket_chaziz
    "bunny_settings" => [
        "stream_api" => "stream api key",
        "stream_library" => 12345,
        "stream_hostname" => "[stream hostname].b-cdn.net",
        "storage_api" => "storage api key",
        "storage_zone" => "storage zone name",
        "pull_zone" => "[pull zone].b-cdn.net",
    ],
    "branding" => [
        "name" => "OpenSB Instance",
        "slogan" => "Insert slogan here",
        "assets" => "/assets/placeholder",
    ],
];
