<?php
require('lib/common.php');
$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) error(__("The video you were looking for cannot be found."));

$query = '';
$count = 0;
if ($videoData['tags']) {
	$count = count(json_decode($videoData['tags']));
	foreach(json_decode($videoData['tags']) as $key=>$value) {
		if ($key >= 1) {
			$query .= "OR";
		}
		$query .= " tags LIKE '%" . addslashes($value) . "%' ";
	}
}
$commentData = query("SELECT $userfields c.id, c.comment, c.author, c.date, c.deleted FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);

if ($count == 0) {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY RAND() LIMIT 6", [$id]);
} else {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY ".$query." DESC, RAND() LIMIT 6", [$id]); //unsafe code, do not deply to production.
}
$totalLikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$videoData['id']]);
$totalDislikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$videoData['id']]);
$combinedRatings = $totalDislikes + $totalLikes;

$allRatings = calculateRatio($totalDislikes, $totalLikes, $combinedRatings);

if (isset($userData)) {
	$rating = result("SELECT rating FROM rating WHERE video=? AND user=?", [$videoData['id'], $userdata['id']]);
	$subscribed = result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$userdata['id'], $videoData['author']]);
} else {
	$rating = 2;
	$subscribed = 0;
}
query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);
$videoData['views']++;

$subCount = fetch("SELECT COUNT(user) FROM subscriptions WHERE user = ?", [$videoData['author']])['COUNT(user)'];

$twig = twigloader();
echo $twig->render('watch.twig', [
	'video' => $videoData,
	'related_videos' => $relatedVideosData,
	'comments' => $commentData,
	'total_likes' => $totalLikes,
	'total_dislikes' => $totalDislikes,
	'rating' => $rating,
	'subscribed' => $subscribed,
	'subCount' => $subCount,
	'videoRatio' => $allRatings,
]);