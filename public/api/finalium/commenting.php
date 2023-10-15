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

    if ($post_data['type'] == "submission") {
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
    }
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

if ($database->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [time() - 60, $auth->getUserID()])) {
    $apiOutput = [
        "error" => "Ratelimited."
    ];
}

if(!isset($apiOutput["error"])) {
    $database->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$post_data["submission"], 0, $post_data['comment'], $auth->getUserID(), time(), 0]);
}

echo json_encode($apiOutput);
