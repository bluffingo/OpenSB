<?php

/**
 * Twig loader, initializes Twig with standard configurations and extensions.
 *
 * @param string $subfolder Subdirectory to use in the templates/ directory.
 * @return \Twig\Environment Twig object.
 */
function twigloader($subfolder = '') {
	global $tplCache, $tplNoCache, $loggedIn, $currentUser;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add squareBracket specific extension
	$twig->addExtension(new SBExtension());

	$twig->addGlobal('logged_in', $loggedIn);
	$twig->addGlobal('current_user', $currentUser);

	return $twig;
}

function videoThumbnail($videodata) {

	$handle = @fopen('http://'.$_SERVER['HTTP_HOST'].'/assets/thumb/'.$videodata.'.png', 'r');
	$twig = twigloader('components');
	//print_r($videodata);
	return $twig->render('videothumbnail.twig', ['data' => $videodata, 'file_exists' => $handle]);
}

function smallVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('smallvideobox.twig', ['data' => $videodata]);
}

function videoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('videobox.twig', ['data' => $videodata]);
}

function watchBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('watchbox.twig', ['data' => $videodata]);
}

function relativeTime($time) {
	$config = [
		'language' => '\RelativeTime\Languages\English',
		'separator' => ', ',
		'suffix' => true,
		'truncate' => 1,
	];

	$relativeTime = new \RelativeTime\RelativeTime($config);

	return $relativeTime->timeAgo($time);
}

function redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
}