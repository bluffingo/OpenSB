<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

// currently selects all uploaded videos
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");

$twig = twigloader();

echo $twig->render('browse.twig', [
	'videos' => $videoData
]);
