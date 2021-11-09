<?php
ini_set('display_errors', 'On');
require('lib/common.php');



// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");
$videoDataRight = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 3");
$featuredVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE flags = 1 ORDER BY v.id DESC"); //i have no clue how should flags even work.
if ($log) {
	$query = implode(', ', array_column(fetchArray(query("SELECT user FROM subscriptions WHERE id = ?", [$userdata['id']])), 'user'));
	if($query != null) {
		$subscriptionVideos = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.author FROM videos v JOIN users u ON v.author = u.id WHERE v.author IN(".$query.") ORDER BY v.id DESC LIMIT 4");
	} else {
		$subscriptionVideos = null;
	}
	$totalSubscribers = result("SELECT SUM(user) FROM subscriptions WHERE user = ?", [$userdata['id']]);
	$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
	$creationDate = result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
	$subscriptionVideos = null;
	$totalSubscribers = 0;
	$totalViews = 0;
	$creationDate = 0;
}
$twig = twigloader();

echo $twig->render('index.twig', [
	'videos' => $videoData,
	'videos_right' => $videoDataRight,
	'subscriptionVideos' => $subscriptionVideos,
	'featuredVideos' => $featuredVideoData,
	'totalSubscribers' => $totalSubscribers,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate
]);
