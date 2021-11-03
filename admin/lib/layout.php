<?php

/**
 * Twig loader, initializes Twig with standard configurations and extensions.
 *
 * @param string $subfolder Subdirectory to use in the templates/ directory.
 * @return \Twig\Environment Twig object.
 */
function _twigloader($subfolder = '') {
	global $tplCache, $tplNoCache, $loggedIn, $currentUser, $frontend;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $frontend . $subfolder);
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add squareBracket admin panel specific extension
	$twig->addExtension(new SBAdminExtension());

	$twig->addGlobal('logged_in', $loggedIn);
	$twig->addGlobal('current_user', $currentUser);

	return $twig;
}

function _profileImage($username) {
	$handle = @fopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/assets/profpic/'.$username.'.png', 'r');
	$twig = twigloader('components');
	return $twig->render('profileimage.twig', ['data' => $username, 'file_exists' => $handle]);
}

function _videoThumbnail($videodata) {
	$handle = @fopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/assets/thumb/'.$videodata.'.png', 'r');
	$twig = twigloader('components');
	return $twig->render('videothumbnail.twig', ['data' => $videodata, 'file_exists' => $handle]);
}

function _smallVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('smallvideobox.twig', ['data' => $videodata]);
}

function _videoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('videobox.twig', ['data' => $videodata]);
}

function _watchBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('watchbox.twig', ['data' => $videodata]);
}

function _relativeTime($time) {
	$config = [
		'language' => '\RelativeTime\Languages\English',
		'separator' => ', ',
		'suffix' => true,
		'truncate' => 1,
	];

	$relativeTime = new \RelativeTime\RelativeTime($config);

	return $relativeTime->timeAgo($time);
}

function _redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
}