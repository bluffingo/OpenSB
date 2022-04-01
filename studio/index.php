<?php
namespace squareBracket\Studio;
require('lib/common.php');

$pageVariable = "studioIndex";

// currently selects all uploaded videos, should turn it into all featured only
$videoData = \squareBracket\query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY views DESC", [$userdata['id']]);


if ($log) {
    $totalSubscribers = \squareBracket\result("SELECT SUM(user) FROM subscriptions WHERE user = ?", [$userdata['id']]);
    $totalViews = \squareBracket\result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
    $creationDate = \squareBracket\result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
	$totalSubscribers = 0;
	$totalViews = 0;
	$creationDate = 0;
}

$twig = \squareBracket\twigloader();

echo $twig->render('studio/index.twig', [
	'videos' => $videoData,
	'totalSubscribers' => $totalSubscribers,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate,
]);
