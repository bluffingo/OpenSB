<?php
ini_set('display_errors', 'On');
require('lib/common.php');



// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.author FROM videos v JOIN users u ON v.author = u.id WHERE NOT flags = 0010 AND NOT v.flags = 0020 ORDER BY v.id DESC LIMIT 10");
$featuredVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.author FROM videos v JOIN users u ON v.author = u.id WHERE flags = 0001 ORDER BY v.id DESC"); //i have no clue how should flags even work.
if ($loggedIn) {
	$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$currentUser['id']]);
	$creationDate = result("SELECT joined FROM users WHERE id = ?", [$currentUser['id']]);
} else {
	$totalViews = 0;
	$creationDate = 0;
}
$twig = twigloader();

echo $twig->render('index.twig', [
	'videos' => $videoData,
	'featuredVideos' => $featuredVideoData,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate
]);
