<?php
require('lib/common.php');
$id = (isset($_GET['i']) ? $_GET['i'] : null);
$ip = (isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']));

$lookData = fetch("SELECT $userfields i.* FROM image i JOIN users u ON i.author = u.id WHERE i.image_id = ?", [$id]);

if (!$lookData) error('404', __("The image/artwork you were looking for cannot be found."));

if (fetch("SELECT COUNT(image_id) FROM image_views WHERE image_id=? AND user=?", [$lookData['image_id'], crypt($ip, "salt, used to encrypt stuff is very important.")])['COUNT(image_id)'] < 1) {
	query("INSERT INTO image_views (image_id, user) VALUES (?,?)",
		[$lookData['image_id'],crypt($ip, "salt, used to encrypt stuff is very important.")]);
}

$viewCount = fetch("SELECT COUNT(image_id) FROM image_views WHERE image_id=?", [$lookData['image_id']])['COUNT(image_id)'];

$twig = twigloader();
echo $twig->render('view.twig', [
	'image' => $lookData,
	'viewCount' => $viewCount,
]);