<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
$commentData = query("SELECT $userfields c.id, c.comment, c.author, c.date, c.deleted FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);
$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");

query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);
$videoData['views']++;

$twig = twigloader();
echo $twig->render('watch.twig', [
    'video' => $videoData,
    'related_videos' => $relatedVideosData,
	'comments' => $commentData
]);