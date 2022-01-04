<?php
require('lib/common.php');

if (isset($_POST['upload'])) {
	$id = $_POST['vid_id'];
	$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
	if ($videoData['author'] != $userdata['id']) {
		die("This is not your video.");
	} else {
	$title	= isset($_POST['title']) ? $_POST['title'] : null;
	$desc	= isset($_POST['desc']) ? $_POST['desc'] : null;
	
	query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
		[$title, $desc, $id]);
	}
	
	redirect(sprintf("/watch.php?v=%s&edited", $id));
}

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) error('404', __("The video you were looking for cannot be found."));

if ($videoData['author'] != $userdata['id']) {
	error('403', __("This is not your video."));
}

$twig = twigloader();
echo $twig->render('studio/edit.twig', [
	'video' => $videoData,
]);