<?php

namespace openSB\FinaliumApi;

global $auth, $betty;

use Orange\Templating;
use Orange\User;

chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';
header('Content-Type: application/json');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "User is banned!!!"
    ];
}

$database = $betty->getBettyDatabase();
$twig = new Templating($betty);

if (isset($post_data['type'])) {
    $author = new User($betty->getBettyDatabase(), $auth->getUserID());

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
if ($database->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()]) ||
    $database->result("SELECT COUNT(*) FROM channel_comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()])
) {
    $apiOutput = [
        "error" => "Please wait at least a minute before commenting again."
    ];
}

if(!isset($apiOutput["error"])) {
    if ($post_data['type'] == "submission") {
        $database->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
            [$post_data["id"], 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
    }

    if ($post_data['type'] == "profile") {
        $database->query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
            [$post_data["id"], 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
    }
}

echo json_encode($apiOutput);
