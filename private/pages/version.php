<?php

namespace OpenSB;

global $twig, $database;

use SquareBracket\VersionNumber;

$data = [
    "developers" => [
        'Chaziz'
    ],
    "software" => [
        'orangeVersion' => [
            'title' => "OpenSB",
            'info' => (new VersionNumber)->getVersionString(),
        ],
        'phpVersion' => [
            'title' => "PHP",
            'info' => phpversion(),
        ],
        'dbVersion' => [
            'title' => "Database software",
            'info' => $database->getVersion(),
        ],
    ],
];

echo $twig->render('version.twig', [
    'data' => $data,
]);