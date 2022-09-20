<?php

namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

if ($userbandata) {
    error(403, __("Banned user, can't continue."));
}

if (isset($_POST['really'])) {
    switch ($_POST['type']) {
        case "video":
            $type = 0;
            $table = "comments";
            $id = ($_POST['vidid'] ?? "");
            $reply_to = ($_POST['reply_to'] ?? "0");
            break;
        case "profile":
            $type = 1;
            $table = "channel_comments";
            $id = ($_POST['uid'] ?? "");
            $reply_to = ($_POST['reply_to'] ?? "0");
            break;
    }
} else {
    die(__("Missing important POST variable."));
}

$comment = [
    'u_name' => $userdata['name'],
    'u_customcolor' => $userdata['customcolor'],
    'comment' => $_POST['comment'],
    'date' => time()
];

if ($type == 0) {
    $sql->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} elseif ($type == 1) {
    $sql->query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} else {
    die(__("Missing important POST variable."));
}

if ($frontendName != "retro") {
    $twig = twigloader();
    echo $twig->render('components/comment.twig', [
        'data' => $comment
    ]);
} else {
    die(__("Your comment has been submitted."));
}