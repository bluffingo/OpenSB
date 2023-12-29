<?php

namespace openSB\FinaliumApi;

global $auth, $orange;

use Orange\Templating;
use Orange\UserData;

chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';

$post_data = json_decode(file_get_contents('php://input'), true);
$legacy_frontend = false;

// If $post_data isn't set, but we're getting a request, that implies the request is from a frontend that uses
// an old version of common.js (like, the Bootstrap frontend or the sbNext/Finalium frontend).
if(!isset($post_data)) {
    $post_data = $_POST;
    $legacy_frontend = true;
}

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "UserData is banned!!!"
    ];
}

$database = $orange->getDatabase();
$twig = new Templating($orange);

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
} elseif($legacy_frontend) {
    // NOTE: This code currently only works for the Bootstrap frontend. The Finalium frontend introduced new parameters
    // ("really" and "type") due to the implementation of profile commenting.
    //
    // However, the Bootstrap frontend's comment.twig template has been patched to use the same variables as the Biscuit
    // frontend, since 2021 openSB and 2023 openSB are completely different things.

    $author = new UserData($orange->getDatabase(), $auth->getUserID());

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

    $apiOutput = $twig->render('components/comment.twig', [
        'comment' => $comment
    ]);

    $id = $post_data["vidid"];
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
if (!$orange->getSettings()->getDevelopmentMode()) {
    if ($database->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()]) ||
        $database->result("SELECT COUNT(*) FROM channel_comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()])
    ) {
        $apiOutput = [
            "error" => "Please wait at least a minute before commenting again."
        ];
    }
}

if(!isset($apiOutput["error"])) {
    if (isset($post_data['type'])) {
        if ($post_data['type'] == "submission") {
            $database->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
                [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
        }

        if ($post_data['type'] == "profile") {
            $database->query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
                [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
        }
    } elseif($legacy_frontend) {
        $database->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
            [$id, 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
    }
}

if($legacy_frontend) {
    echo $apiOutput; // TODO: error output will be fucked.
} else {
    echo json_encode($apiOutput);
}