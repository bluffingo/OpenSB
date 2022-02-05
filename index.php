<?php
ini_set('display_errors', 'On');
require('lib/common.php');

$nonFunctionalShit = true;
$pageVariable = "index";

// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND() LIMIT 12");
$videoDataRecentlyViewed = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, category_id, v.author, v.most_recent_view FROM videos v JOIN users u ON v.author = u.id ORDER BY v.most_recent_view DESC LIMIT 4");
$videoDataRight = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 7");
if ($frontend == "2012") {
$featuredVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND() DESC LIMIT 5"); //i have no clue how should flags even work.
} else {
$featuredVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND() DESC LIMIT 1"); //i have no clue how should flags even work.
}
// moved total subscribers to layout.php for 2015 hitchhiker
if ($log) {
	$query = implode(', ', array_column(fetchArray(query("SELECT user FROM subscriptions WHERE id = ?", [$userdata['id']])), 'user'));
	if($query != null) {
		$subscriptionVideos = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, v.author FROM videos v JOIN users u ON v.author = u.id WHERE v.author IN(".$query.") ORDER BY v.id DESC LIMIT 4");
	} else {
		$subscriptionVideos = null;
	}
	$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
	$creationDate = result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
	$subscriptionVideos = null;
	$totalViews = 0;
	$creationDate = 0;
}

$stats = fetch("SELECT (SELECT COUNT(*) FROM videos) videocount, (SELECT COUNT(*) FROM image) imagecount");

$twig = twigloader();

echo $twig->render('index.twig', [
	'videos' => $videoData,
	'videos_right' => $videoDataRight,
	'recently_viewed' => $videoDataRecentlyViewed,
	'subscriptionVideos' => $subscriptionVideos,
	'featuredVideos' => $featuredVideoData,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate,
	'updated' => (isset($_GET['updated']) ? true : false),
	'stats' => $stats,
]);
