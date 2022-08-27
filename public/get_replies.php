<?php

namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';
if (!isset($_POST['comment_id'])) {
    die();
}

$commentData = $sql->query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, c.reply_to FROM comments c JOIN users u ON c.author = u.id WHERE c.reply_to = ? ORDER BY c.date DESC", [$_POST['comment_id']]);

$twig = twigloader();
$template = $twig->createTemplate('{% for comment in comments %}{{ comment(comment) }}{% endfor %}');
echo $template->render([
    'comments' => $commentData
]);