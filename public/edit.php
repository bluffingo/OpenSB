<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

if ($userbandata) {
    error(403, __("You are currently banned and cannot proceed with this action."));
}

if (isset($_POST['upload'])) {
    $id = $_POST['vid_id'];
    $videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
    if ($videoData['author'] != $userdata['id']) {
        error('403', __("You cannot modify someone else's submission."));
    } else {
        $title = $_POST['title'] ?? null;
        $desc = $_POST['desc'] ?? null;

        $sql->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
            [$title, $desc, $id]);
        die("Your submission's information has been modified.");
    }
}

$id = ($_GET['v'] ?? null);

$videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) error('404', __("This submission cannot be found."));

if ($videoData['author'] != $userdata['id']) {
    error('403', __("You cannot modify someone else's submission."));
}

$twig = twigloader();
echo $twig->render('edit.twig', [
    'video' => $videoData,
]);