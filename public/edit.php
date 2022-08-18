<?php
namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

if (isset($_POST['upload'])) {
	$id = $_POST['vid_id'];
	$videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
	if ($videoData['author'] != $userdata['id']) {
		die("This is not your video.");
	} else {
	$title	= $_POST['title'] ?? null;
	$desc	= $_POST['desc'] ?? null;

        $sql->query("UPDATE videos SET title = ?, description = ? WHERE video_id = ?",
		[$title, $desc, $id]);
	}
	
	redirect(sprintf("/watch.php?v=%s&edited", $id));
}

$id = ($_GET['v'] ?? null);

$videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) error('404', __("The video you were looking for cannot be found."));

if ($videoData['author'] != $userdata['id']) {
    error('403', __("This is not your video."));
}

$twig = twigloader();
echo $twig->render('edit.twig', [
	'video' => $videoData,
]);