<?php

namespace openSB;

global $betty, $bettyTemplate, $sql, $userdata, $userbandata, $auth;

// TODO: this should be rewritten into /api/finalium/commenting.php -chaziz 9/9/2023

use Orange\Templating;
use Orange\User;

require_once dirname(__DIR__) . '/private/class/common.php';

if ($userbandata) {
    error(403, __("You are currently banned and cannot proceed with this action."));
}

// simple shit fix for shitty finalium bug that dates from 2021 -grkb 4/12/2023
if ($_POST["comment"] == "")
{
    die();
}

// Fuck -grkb 4/19/2023
if (strlen($_POST["comment"]) > 1000) {
	die("Too long.");
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
            die("Commenting on profiles is no longer supported and will only be brought back once the code for commenting has been rewritten to use Orange.");
            $type = 1;
            $table = "channel_comments";
            $id = ($_POST['uid'] ?? "");
            $reply_to = ($_POST['reply_to'] ?? "0");
            break;
    }
} else {
    die(__("Missing important POST variable."));
}

if ($betty->getLocalOptions()["development"] ?? false) {
    $author = new User($betty->getBettyDatabase(), $auth->getUserID());
    $comment = [
        "id" => 123456789,
        "posted_id" => 987654321,
        "post" => $_POST['comment'],
        "posted" => time(),
        "author" => [
            "id" => $auth->getUserID(),
            "info" => $author->getUserArray(),
        ],
    ];
} else {
    if ($sql->result("SELECT COUNT(*) FROM comments WHERE date > ? AND author = ?", [time() - 60, $userdata["id"]])) {
        $betty->Notification("Please wait a minute before commenting again.", "/watch.php?v=" . $id);
    }

    $comment = [
        'u_name' => $userdata['name'],
        'u_customcolor' => $userdata['customcolor'],
        'comment' => $_POST['comment'],
        'date' => time()
    ];
}

if ($type == 0) {
    $sql->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} elseif ($type == 1) {
    $sql->query("INSERT INTO channel_comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
        [$id, $reply_to, $_POST['comment'], $userdata['id'], time(), 0]);
} else {
    die(__("Missing important POST variable."));
}

if ($betty->getLocalOptions()["development"] ?? false) {
    // new implementation that's still a placeholder
    $twig = new Templating($betty);
    echo $twig->render('components/_comment.twig', [
        'comment' => $comment
    ]);
} else {
    // use old placeholder implementation
    if ($bettyTemplate != "qobo") {
        $twig = twigloader();
        echo $twig->render('components/comment.twig', [
            'data' => $comment
        ]);
    } else {
        $betty->Notification("Your comment has been successfully posted.", "/watch.php?v=" . $id, "success");
    }
}