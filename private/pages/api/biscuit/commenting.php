<?php

namespace OpenSB;

global $auth, $orange, $twig, $isDebug;

use SquareBracket\UserData;

chdir('../../');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    echo json_encode(["error" => "User is banned."]);
    exit;
}

if (!isset($post_data['type']) || !isset($post_data['comment'])) {
    echo json_encode($apiOutput);
    exit;
}

$database = $orange->getDatabase();
$author = new UserData($database, $auth->getUserID());
$commentText = trim($post_data['comment']);

if ($commentText === "" || $commentText === null) {
    echo json_encode(["error" => "This comment is invalid."]);
    exit;
}

if (strlen($commentText) > 1000) {
    echo json_encode(["error" => "This comment is too long."]);
    exit;
}

if (!$isDebug) {
    $timeLimit = time() - 15;
    $userId = $auth->getUserID();
    if ($database->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [$timeLimit, $userId]) ||
        $database->result("SELECT COUNT(*) FROM channel_comments WHERE date > ? AND author = ?", [$timeLimit, $userId]) ||
        $database->result("SELECT COUNT(*) FROM journal_comments WHERE date > ? AND author = ?", [$timeLimit, $userId])
    ) {
        echo json_encode(["error" => "Please wait at least 15 seconds before commenting again."]);
        exit;
    }
}

$comment = [
    "id" => 0, // todo
    "posted_id" => $post_data['id'],
    "post" => $commentText,
    "posted" => time(),
    "author" => [
        "id" => $auth->getUserID(),
        "info" => $author->getUserArray(),
    ],
    "replies" => []
];

$html = $twig->render('components/_comment.twig', ['comment' => $comment]);

$id = $post_data["id"];
$replyTo = $post_data['reply_to'] ?? 0;
$userId = $auth->getUserID();
$currentTime = time();
$table = '';

switch ($post_data['type']) {
    case 'submission':
        $table = 'comments';
        break;
    case 'profile':
        $table = 'channel_comments';
        break;
    case 'journal':
        $table = 'journal_comments';
        break;
    default:
        echo json_encode(["error" => "Invalid comment type."]);
        exit;
}

$database->query(
    "INSERT INTO {$table} (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
    [$id, $replyTo, $commentText, $userId, $currentTime, 0]
);

$apiOutput = [
    "comment" => $comment,
    "html" => $html,
];

echo json_encode($apiOutput);