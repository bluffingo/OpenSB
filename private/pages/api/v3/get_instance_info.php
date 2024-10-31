<?php

namespace OpenSB;

global $database;

use SquareBracket\VersionNumber;

header('Content-Type: application/json');

$version = new VersionNumber();

$apiOutput = [
    'instance' => [
        'version' => $version->getVersionString(),
    ],
];

echo json_encode($apiOutput);
