<?php

namespace openSB\API;
chdir('../../');
$rawOutputRequired = true;
require dirname(__DIR__) . '/../../private/class/common.php';

header('Content-Type: application/json');

$username = (isset($_GET['name']) ? $_GET['name'] : null);

$id = (isset($_GET['id']) ? $_GET['id'] : null);

if (isset($_GET['id'])) {
    $userData = $sql->fetch("SELECT * FROM users WHERE id = ?", [$id]);
} else {
    $userData = $sql->fetch("SELECT * FROM users WHERE name = ?", [$username]);
}

if (!$userData) {
    $apiOutput = ['error' => "No user specified or invalid user ID", 'code' => "52e44102"];

    echo json_encode($apiOutput);
    die();
}

// TODO: comments? likes?
$apiOutput = [
    'id' => $userData['id'],
    'displayName' => $userData['title'],
    'username' => $userData['name'],
    'joinDate' => $userData['joined'],
    'lastPost' => $userData['lastpost'],
    'lastConnection' => $userData['lastview'],
    'profileColor' => $userData['customcolor'],
    'info' => [ // supposed to be a "videoflags" object
        'description' => $userData['about'],
    ],
];

echo json_encode($apiOutput);
