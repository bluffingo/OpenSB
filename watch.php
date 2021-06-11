<?php
require('lib/common.php');
$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
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
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY RAND()");
} else {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY ".$query." DESC, RAND()"); //unsafe code, do not deply to production.
}
$totalLikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$videoData['id']]);
$totalDislikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$videoData['id']]);
if (isset($currentUser)) {
	$rating = result("SELECT rating FROM rating WHERE video=? AND user=?", [$videoData['id'], $currentUser['id']]);
} else {
	$rating = 2;
}
query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);
$videoData['views']++;

$twig = twigloader();
echo $twig->render('watch.twig', [
	'video' => $videoData,
	'related_videos' => $relatedVideosData,
	'comments' => $commentData,
	'total_likes' => $totalLikes,
	'total_dislikes' => $totalDislikes,
	'rating' => $rating
]);