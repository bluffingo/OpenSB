<?php

namespace OpenSB;

global $twig, $database, $auth;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;

$id = ($_GET['j'] ?? null);

$data = $database->fetch("SELECT j.* FROM journals j WHERE j.id = ?", [$id]);

if(!$data) {
    UnorganizedFunctions::Notification("This journal does not exist.", "/");
}

if ($auth->getUserID() == $this->data["author"]) { $owner = true; } else { $owner = false; }

$author = new UserData($database, $data["author"]);
$comments = new CommentData($database, CommentLocation::Journal, $id);

$data = [
    "is_owner" => $owner,
    "int_id" => $this->data["id"],
    "title" => $this->data["title"],
    "contents" => $this->data["post"],
    "published" => $this->data["date"],
    "author" => [
        "id" => $this->data["author"],
        "info" => $this->author->getUserArray(),
    ],
    "comments" => $this->comments->getComments(),
];

echo $twig->render('read.twig', [
    'data' => $data,
]);