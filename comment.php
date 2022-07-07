<?php

namespace squareBracket;

require('lib/common.php');
if (isset($_POST['really'])) {
    switch ($_POST['type']) {
        case "video":
            $type = 0;
            $table = "comments";
            $id = (isset($_POST['vidid']) ? $_POST['vidid'] : "");
            $reply_to = (isset($_POST['reply_to']) ? $_POST['reply_to'] : "0");
            break;
        case "profile":
            $type = 1;
            $table = "channel_comments";
            $id = (isset($_POST['uid']) ? $_POST['uid'] : "");
            $reply_to = (isset($_POST['reply_to']) ? $_POST['reply_to'] : "0");
            break;
    }
} else {
    die(__("this is invalid"));
}

$comment = [
    'u_name' => $userdata['name'],
    'u_customcolor' => $userdata['customcolor'],
    'comment' => $_POST['comment'],
    'date' => time()
];

if ($type == 0) {
    query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} elseif ($type == 1) {
    query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} else {
    die(__("this is still invalid"));
}

$twig = twigloader();
echo $twig->render('components/comment.twig', [
    'data' => $comment
]);