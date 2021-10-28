<?php 
$rawOutputRequired = true;
$videoID = $_GET['video_id'];
foreach($_GET as $arg => $val) 
{
	switch($arg) {
		case "action_get_related_videos_component":
			$type = 0;
			break;
		case "action_get_related_playlist_component":
			$type = 1;
			break;
		case "action_get_user_videos_component":
			$type = 2;
			break;
	}
}
require('lib/common.php');
switch($type) {
	case 0:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");
		break;
	case 1: 
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 ORDER BY RAND() LIMIT 0", [$videoID]);
		break;
	case 2:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 AND v.author = ? ORDER BY RAND() LIMIT 6", [$videoID, $videoInfo['author']]);
		break;
	default:
		$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 ORDER BY RAND() LIMIT 6", [$videoID]);
		break;
}
header('Content-type: application/xml'); 
$twig = twigloader();
echo $twig->render('components/ajaxsidebar.twig', [
	'videos' => $videoData,
	'type' => $type
]);

