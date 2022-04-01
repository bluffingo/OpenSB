<?php
namespace squareBracket\Studio;
require('lib/common.php');

if (isset($_POST['upload'])) {
	$id = $_POST['vid_id'];
	$videoData = \squareBracket\fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
	if ($videoData['author'] != $userdata['id']) {
		die("This is not your video.");
	} else {
	$title	= isset($_POST['title']) ? $_POST['title'] : null;
	$desc	= isset($_POST['desc']) ? $_POST['desc'] : null;
	
	\squareBracket\query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
		[$title, $desc, $id]);
	}
	
	redirect(sprintf("/watch.php?v=%s&edited", $id));
}

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = \squareBracket\fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) \squareBracket\error('404', __("The video you were looking for cannot be found."));

if ($videoData['author'] != $userdata['id']) {
    \squareBracket\error('403', __("This is not your video."));
}

$twig = \squareBracket\twigloader();
echo $twig->render('studio/edit.twig', [
	'video' => $videoData,
]);