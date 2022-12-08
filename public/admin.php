<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

if ($userdata['powerlevel'] < 3) error('403', "You shouldn't be here, get out!");

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'delete_comment') {
        if (isset($_GET['id'])) {
            $sql->query("DELETE FROM comments WHERE comment_id = ?", [$_GET['id']]);
        } else {
            error('400', 'you forgot the id.');
        }
    }
}

$latestRegisteredUsers = $sql->query("SELECT id, name, joined FROM users ORDER BY joined DESC LIMIT 15");
$latestSeenUsers = $sql->query("SELECT id, name, lastview FROM users ORDER BY lastview DESC LIMIT 15");
$videoData = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id ORDER BY v.time DESC LIMIT 7");
$comments = $sql->fetchArray($sql->query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, v.title, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c JOIN users u ON c.author = u.id JOIN videos v ON c.id = v.video_id ORDER BY c.date DESC"));
foreach ($comments as &$comment) {
    $comment['allVideos'] = Users::getUserVideoCount($comment['author']);
    $comment['allFavorites'] = Users::getUserFavoriteCount($comment['author']);
}

//$thingsToCount = ['comments', 'users', 'videos', 'views', 'messages', 'favorites'];
$thingsToCount = ['comments', 'users', 'videos', 'views', 'favorites', 'bans'];

$query = "SELECT ";
foreach ($thingsToCount as $thing) {
    if ($query != "SELECT ") $query .= ", ";
    $query .= sprintf("(SELECT COUNT(*) FROM %s) %s", $thing, $thing);
}
$count = $sql->fetch($query);

$twig = twigloader();
echo $twig->render('admin.twig', [
    'latest_registered_users' => $latestRegisteredUsers,
    'latest_seen_users' => $latestSeenUsers,
    'things_to_count' => $thingsToCount,
    'count' => $count,
    'videos' => $videoData,
    'comments' => $comments
]);
