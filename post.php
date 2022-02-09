<?php

// TODO: IMPLEMENT POST TYPE

$pageVariable = "watch";

require('lib/common.php');
$id = (isset($_GET['id']) ? $_GET['id'] : null);
$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']));

$postData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.id = ?", [$id]);

if (!$postData) error('404', __("The post you were looking for cannot be found."));

$query = '';
$count = 0;

//FIXME: Why the fuck are the tags based with JSON?

if ($postData['tags']) {
	$count = count(json_decode($postData['tags']));
	foreach(json_decode($postData['tags']) as $key=>$value) {
		if ($key >= 1) {
			$query .= "OR";
		}
		$query .= " tags LIKE '%" . addslashes($value) . "%' ";
	}
}
$commentData = query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);

if ($count == 0) {
	$relatedPosts = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY RAND() LIMIT 6", [$id]);
} else {
	$relatedPosts = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY ".$query." DESC, RAND() LIMIT 6", [$id]); //unsafe code, do not deply to production.
}
$totalLikes = result("SELECT COUNT(rating) FROM rating WHERE  video=? AND rating=1", [$postData['id']]);
$totalDislikes = result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$postData['id']]);
$combinedRatings = $totalDislikes + $totalLikes;

$allRatings = calculateRatio($totalDislikes, $totalLikes, $combinedRatings);

$allPosts = result("SELECT COUNT(id) FROM videos WHERE author=?", [$postData['u_id']]);

if ( isset( $userdata ) && !empty( $_GET['oldPlayer'] ) ) {
	$rating = result("SELECT rating FROM rating WHERE video=? AND user=?", [$postData['id'], $userdata['id']]);
	$subscribed = result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$userdata['id'], $postData['author']]);
} else {
	$rating = 2;
	$subscribed = 0;
}
if (fetch("SELECT COUNT(video_id) FROM views WHERE video_id=? AND user=?", [$postData['video_id'], crypt($ip, "salt, used to encrypt stuff is very important.")])['COUNT(video_id)'] < 1) {
	query("INSERT INTO views (video_id, user) VALUES (?,?)",
		[$postData['video_id'],crypt($ip, "salt, used to encrypt stuff is very important.")]);
}

$subCount = fetch("SELECT COUNT(user) FROM subscriptions WHERE user=?", [$postData['author']])['COUNT(user)'];
$commentCount = fetch("SELECT COUNT(id) FROM comments WHERE id=?", [$postData['video_id']])['COUNT(id)']; //broken,, fix -gr 11/3/2021
$viewCount = fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$postData['video_id']])['COUNT(video_id)'];

query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);


$previousRecentView = result("SELECT most_recent_view from videos WHERE video_id = ?", [$id]);
$currentTime = time();

query("UPDATE videos SET most_recent_view = ? WHERE video_id = ?", [$currentTime,$id]); 

$twig = twigloader();
echo $twig->render('post.twig', [
	'post' => $postData,
	'related_posts' => $relatedPosts,
	'comments' => $commentData,
	'total_likes' => $totalLikes,
	'total_dislikes' => $totalDislikes,
	'total_rating' => $combinedRatings,
	'rating' => $rating,
	'subscribed' => $subscribed,
	'subCount' => $subCount,
	'comCount' => $commentCount,
	'viewCount' => $viewCount,
	'postRatio' => $allRatings,
	'recentView' => $previousRecentView,
	'allPosts' => $allPosts,
]);
