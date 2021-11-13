<?php
require('lib/common.php');
if(isset($_POST['limit'])) {
	$limit = (isset($_POST['limit']) ? $_POST['limit'] : 6);
	$offset = (isset($_POST['from']) ? $_POST['from'] : 0);
	$user = (isset($_POST['user']) ? $_POST['user'] : 0);
	
	$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? LIMIT ? OFFSET ?", [$user, $limit, $offset]);
	
	$twig = twigloader();
	echo $twig->render('components/videolist.twig', [
		'videos' => $videoData,
	]);
}
