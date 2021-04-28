<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");

//todo: implement markdown renderer and make this use sql
$comment = [
	'u_username' => "icanttellyou",
	'comment' => "testing comment template lol",
	'time' => 587678525
];

$twig = twigloader();

echo $twig->render('watch.twig', [
    'video' => $videoData,
    'related_videos' => $relatedVideosData, 
	'comments' => $comment
]);