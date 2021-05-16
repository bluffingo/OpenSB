<?php

/**
 * Twig loader, initializes Twig with standard configurations and extensions.
 *
 * @param string $subfolder Subdirectory to use in the templates/ directory.
 * @return \Twig\Environment Twig object.
 */
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\Extra\Markdown\MarkdownExtension;

function twigloader($subfolder = '') {
	global $tplCache, $tplNoCache, $loggedIn, $currentUser, $theme;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	
	// Add squareBracket specific extension
	$twig->addExtension(new SBExtension());
	
	$twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
    public function load($class) {
        if (MarkdownRuntime::class === $class) {
            return new MarkdownRuntime(new DefaultMarkdown());
        }
    }
	});
	$twig->addExtension(new MarkdownExtension());
	
	$twig->addGlobal('logged_in', $loggedIn);
	$twig->addGlobal('current_user', $currentUser);
	$twig->addGlobal('theme', $theme);

	return $twig;
}

function comment($comment) {
	$twig = twigloader('components');
	return $twig->render('comment.twig', ['data' => $comment]);
}

function profileImage($username) {
	$connectOptions = array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  
	$handle = @fopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/assets/profpic/'.$username.'.png', 'r', false, stream_context_create($connectOptions));
	$twig = twigloader('components');
	return $twig->render('profileimage.twig', ['data' => $username, 'file_exists' => $handle]);
}

function videoThumbnail($videodata) {
	$connectOptions = array(
		"ssl" => array(
			"verify_peer" => false,
			"verify_peer_name" => false,
		),
	);  
	$handle = @fopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/assets/thumb/'.$videodata.'.png', 'r', false, stream_context_create($connectOptions));
	$twig = twigloader('components');
	return $twig->render('videothumbnail.twig', ['data' => $videodata, 'file_exists' => $handle]);
}

function browseVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('browsevideobox.twig', ['data' => $videodata]);
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