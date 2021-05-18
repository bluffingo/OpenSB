<?php
require('lib/common.php');

$min = ((isset($_GET['page']) ? $_GET['page'] : 1) - 1) * 20;
$max = $min + 20;

// currently selects all uploaded videos
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT ?, ?", [$min, $max]);

$pageCount = ceil(fetch("SELECT COUNT(*) FROM videos")['COUNT(*)'] / 20);
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/browse.php?page=';

$twig = twigloader();

echo $twig->render('browse.twig', [
	'videos' => $videoData,
	'currentPage' => (isset($_GET['page']) ? $_GET['page'] : 1),
	'pageCount' => $pageCount,
	'url' => $url
]);
