<?php
require('lib/common.php');

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT * FROM videos WHERE video_id = ?", [$id]);

$relatedVideosData = query("SELECT video_id, title, description, time, views, author FROM videos ORDER BY id DESC");

$twig = twigloader();

echo $twig->render('watch.twig', [
	'video' => $videoData,
	'related_videos' => $relatedVideosData
]);
