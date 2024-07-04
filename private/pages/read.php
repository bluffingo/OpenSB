<?php

namespace OpenSB;

global $twig, $database, $auth;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;

$id = ($_GET['j'] ?? null);

$data = $database->fetch("SELECT j.* FROM journals j WHERE j.id = ?", [$id]);

if (!$data) {
    UnorganizedFunctions::Notification("This journal does not exist.", "/");
}

if ($auth->getUserID() == $data["author"]) {
    $owner = true;
} else {
    $owner = false;
}

$author = new UserData($database, $data["author"]);
$comments = new CommentData($database, CommentLocation::Journal, $id);

$data = [
    "is_owner" => $owner,
    "int_id" => $data["id"],
    "title" => $data["title"],
    "contents" => $data["post"],
    "published" => $data["date"],
    "author" => [
        "id" => $data["author"],
        "info" => $author->getUserArray(),
    ],
    "comments" => $comments->getComments(),
];

echo $twig->render('read_journal.twig', [
    'data' => $data,
]);