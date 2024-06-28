<?php

namespace OpenSB;

global $database;

// TODO: get this working with modern opensb code.

header('Content-Type: application/json');

$limit = (isset($_GET['limit']) ? $_GET['limit'] : 10);
$offset = (isset($_GET['offset']) ? $_GET['offset'] : 0);
$id = ($_GET['v'] ?? null);

$comments = $database->query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC LIMIT ? OFFSET ?", [$id, $limit, $offset]);
$commentCount = $database->fetch("SELECT COUNT(id) FROM comments WHERE id=?", [$id])['COUNT(id)'];

$apiOutput = [];
foreach ($comments as $comment) {
    $apiOutput[] =
        [
            'id' => $comment['comment_id'],
            'comment' => $comment['comment'],
            'author' => [
                'id' => $comment['u_id'],
                'name' => $comment['u_name'],
                'color' => $comment['u_customcolor'],
            ]
        ];
}

echo json_encode(array('submissions' => $apiOutput, 'count' => $commentCount));