<?php
require('lib/common.php');

// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT video_id, title, description, time, views, author FROM videos ORDER BY id DESC");

$twig = twigloader();

echo $twig->render('index.twig', [
	'videos' => $videoData
]);
