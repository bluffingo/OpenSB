<?php

namespace OpenSB;

global $auth, $orange, $twig;

use SquareBracket\Templating;
use SquareBracket\UserData;

chdir('../../');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "UserData is banned!!!"
    ];
}

$database = $orange->getDatabase();

if (isset($post_data['type'])) {
    // Biscuit frontend outputs in JSON.
    header('Content-Type: application/json');

    $author = new UserData($orange->getDatabase(), $auth->getUserID());

    //if ($post_data['type'] == "submission") {
        $comment = [
            "id" => 123456789,
            "posted_id" => 987654321,
            "post" => $post_data['comment'],
            "posted" => time(),
            "author" => [
                "id" => $auth->getUserID(),
                "info" => $author->getUserArray(),
            ],
        ];
        $html = $twig->render('components/_comment.twig', [
            'comment' => $comment
        ]);

        $apiOutput = [
            "comment" => $comment,
            "html" => $html,
        ];
    //}

    $id = $post_data["id"];
}

if (ctype_space($post_data["comment"]) || $post_data["comment"] === "" || $post_data["comment"] === null) {
    $apiOutput = [
        "error" => "This comment is invalid."
    ];
}

if (strlen($post_data["comment"]) > 1000) {
    $apiOutput = [
        "error" => "This comment is way too long."
    ];
}

//TODO: Innerjoin???
//if (!$orange->getSettings()->getDevelopmentMode()) {
    if ($database->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()]) ||
        $database->result("SELECT COUNT(*) FROM channel_comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()]) ||
        $database->result("SELECT COUNT(*) FROM journal_comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()])
    ) {
        $apiOutput = [
            "error" => "Please wait at least a minute before commenting again."
        ];
    }
//}

if(!isset($apiOutput["error"])) {
    if (isset($post_data['type'])) {
        if ($post_data['type'] == "submission") {
            $database->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
                [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
        } elseif ($post_data['type'] == "profile") {
            $database->query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
                [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
        } elseif ($post_data['type'] == "journal") {
            $database->query("INSERT INTO journal_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
                [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
        }
    }
}

echo json_encode($apiOutput);