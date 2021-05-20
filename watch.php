<?php
require('lib/common.php');
$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
$query = '';
if ($videoData['tags']) {
	foreach(json_decode($videoData['tags']) as $key=>$value) {
		if ($key < 1) {
			$query .= "WHERE";
		} else if ($key >= 1) {
			$query .= "OR";
		}
		$query .= " tags LIKE '%" . addslashes($value) . "%' ";
	}
}
$commentData = query("SELECT $userfields c.id, c.comment, c.author, c.date, c.deleted FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);
$count = fetch("SELECT COUNT(video_id) FROM videos ".$query);
if ($count['COUNT(video_id)'] === 0) {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");
} else {
	$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ".$query." ORDER BY v.id DESC"); //unsafe code, do not deply to production.
}
query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);
$videoData['views']++;

$twig = twigloader();
echo $twig->render('watch.twig', [
	'video' => $videoData,
	'related_videos' => $relatedVideosData,
	'comments' => $commentData
]);