<?php
require('lib/common.php');

// currently selects all uploaded videos, should turn it into all featured only
$videoData = query("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY v.views DESC", [$userdata['id']]);

if ($log) {
	$totalSubscribers = result("SELECT SUM(user) FROM subscriptions WHERE user = ?", [$userdata['id']]);
	$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
	$creationDate = result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
	$totalSubscribers = 0;
	$totalViews = 0;
	$creationDate = 0;
}

$twig = twigloader();

echo $twig->render('studio/index.twig', [
	'videos' => $videoData,
	'totalSubscribers' => $totalSubscribers,
	'totalViews' => $totalViews,
	'creationDate' => $creationDate,
]);
