<?php
require('lib/common.php');

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['user'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['user']]);
}

$customProfile = fetch("SELECT * FROM channel_settings WHERE user = ?", [$userpagedata['id']]);

$twig = twigloader();
	echo $twig->render('writeProfileComment.twig', [
		'id' => $userpagedata['id'],
		'name' => $userpagedata['name'],
		'userpagedata' => $userpagedata,
		'customProfile' => $customProfile,
	]);