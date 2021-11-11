<?php
require('lib/common.php');

// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.id FROM videos v JOIN users u ON v.author = u.id");

$twig = twigloader();

echo $twig->render('admin/index.twig', [
	'videos' => $videoData
]);
