<?php

namespace OpenSB;

global $twig, $database, $auth, $enableFederatedStuff, $isDebug;

use SquareBracket\CommentData;
use SquareBracket\CommentLocation;
use SquareBracket\UnorganizedFunctions;
use SquareBracket\UserData;

$id = ($_GET['j'] ?? null);

if ($enableFederatedStuff) {
    if (!$auth->isUserLoggedIn())
    {
        UnorganizedFunctions::Notification("Please login to continue.", "/login.php");
    }

    $data = $database->fetch("SELECT p.* FROM posts p WHERE p.id = ?", [$id]);

    if (!$data && $isDebug) {
        $data = $database->fetchArray($database->query("SELECT p.* FROM posts p",));
        echo $twig->render('debug_posts.twig', [
            'data' => $data,
        ]);
        die();
    }

    echo $twig->render('read_journal.twig', [
        'data' => $data,
    ]);
} else {
    // not federated, use journals
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

    echo $twig->render('read.twig', [
        'data' => $data,
    ]);
}