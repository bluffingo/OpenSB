<?php

namespace openSB;

require dirname(__DIR__) . '/private/class/common.php';

$revisions = $sql->fetchArray($sql->query("SELECT $userfields r.page, r.revision, r.size, r.sizediff, r.time, r.description, r.type, r.comment_id FROM revisions r JOIN users u ON r.author = u.id ORDER BY r.time DESC, r.id DESC LIMIT 50"));
$revisionListActual = array();

foreach ($revisions as $revision) {
    if ($revision["type"] == 1) { // posts
        $title = $sql->result("SELECT title FROM videos WHERE video_id=?", [$revision['page']]);
        if (!empty($title)) {
            $revision["note"] = vsprintf("Post %s uploaded", [$title]);
        } else {
            $revision["note"] = vsprintf("Post (ID %s) does not exist", [$revision['page']]);
        }
    } elseif ($revision["type"] == 2) { // comments
        $comment = $sql->result("SELECT comment FROM comments WHERE comment_id=?", [$revision['comment_id']]);
        if (!empty($comment)) {
            $revision["note"] = $comment;
        } else {
            $revision["note"] = vsprintf("Video comment (ID %s) does not exist", [$revision['comment_id']]);
        }
    } elseif ($revision["type"] == 3) { // user comments
        $comment = $sql->result("SELECT comment FROM channel_comments WHERE comment_id=?", [$revision['comment_id']]);
        if (!empty($comment)) {
            $revision["note"] = $comment;
        } else {
            $revision["note"] = vsprintf("Profile comment (ID %s) does not exist", [$revision['comment_id']]);
        }
    }
    array_push($revisionListActual, $revision);
}

$twig = twigloader();
echo $twig->render('recentchanges.twig', [
    'revisions' => $revisionListActual
]);
