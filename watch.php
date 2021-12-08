<?php
require('lib/common.php');
$id = (isset($_GET['v']) ? $_GET['v'] : null);
$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']));

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) error('404', __("The video you were looking for cannot be found."));


if ( isset( $_GET['oldPlayer'] ) && !empty( $_GET['oldPlayer'] ) ) {
	$oldPlayer = true;
} else {
	$oldPlayer = false;
}

// using comment.php on 2008 would require clunky javascript
if ($frontend == "2008") {
	if (isset($_POST['comment_submit'])) {
		query("INSERT INTO comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
		[$videoData['video_id'],$_POST['comment_text'],$userdata['id'],time(),0]);
	}
}

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
$commentData = query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);

if ($count == 0) {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY RAND() LIMIT 6", [$id]);
} else {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY ".$query." DESC, RAND() LIMIT 6", [$id]); //unsafe code, do not deply to production.
}
$totalLikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$videoData['id']]);
$totalDislikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$videoData['id']]);
$combinedRatings = $totalDislikes + $totalLikes;

$allRatings = calculateRatio($totalDislikes, $totalLikes, $combinedRatings);

if ( isset( $userdata ) && !empty( $_GET['oldPlayer'] ) ) {
	$rating = result("SELECT rating FROM rating WHERE video=? AND user=?", [$videoData['id'], $userdata['id']]);
	$subscribed = result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$userdata['id'], $videoData['author']]);
} else {
	$rating = 2;
	$subscribed = 0;
}
if (fetch("SELECT COUNT(video_id) FROM views WHERE video_id=? AND user=?", [$videoData['video_id'], crypt($ip, "salt, used to encrypt stuff is very important.")])['COUNT(video_id)'] < 1) {
	query("INSERT INTO views (video_id, user) VALUES (?,?)",
		[$videoData['video_id'],crypt($ip, "salt, used to encrypt stuff is very important.")]);
}

$subCount = fetch("SELECT COUNT(user) FROM subscriptions WHERE user=?", [$videoData['author']])['COUNT(user)'];
$commentCount = fetch("SELECT COUNT(id) FROM comments WHERE id=?", [$videoData['video_id']])['COUNT(id)']; //broken,, fix -gr 11/3/2021
$viewCount = fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$videoData['video_id']])['COUNT(video_id)'];

query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);

/* for 2008 */
$currentTime = time();
$allStars = $allRatings / 20;

query("UPDATE videos SET most_recent_view = ? WHERE video_id = ?", [$currentTime,$id]); 

$twig = twigloader();
echo $twig->render('watch.twig', [
	'video' => $videoData,
	'related_videos' => $relatedVideosData,
	'comments' => $commentData,
	'total_likes' => $totalLikes,
	'total_dislikes' => $totalDislikes,
	'total_rating' => $combinedRatings,
	'rating' => $rating,
	'subscribed' => $subscribed,
	'subCount' => $subCount,
	'comCount' => $commentCount,
	'viewCount' => $viewCount,
	'videoRatio' => $allRatings,
	'oldPlayer' => $oldPlayer,
	'starRatio' => $allStars,
]);