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
	global $tplCache, $tplNoCache, $loggedIn, $currentUser, $theme, $languages, $frontend, $menuLinks;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $frontend . '/' . $subfolder);
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
	$twig->addGlobal('menu_links', $menuLinks);
	$twig->addGlobal('current_user', $currentUser);
	$twig->addGlobal('theme', $theme);
	$twig->addGlobal('glob_languages', $languages);
	$twig->addGlobal("page_url", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	$twig->addGlobal("domain", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
	$twig->addGlobal('stats', fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount"));

	return $twig;
}

function error($message) {
	$twig = twigloader();
	echo $twig->render('_error.twig', ['err_message' => $message]);
	die();
}

function comment($comment) {
	$twig = twigloader('components');
	return $twig->render('comment.twig', ['data' => $comment]);
}

function profileImage($username) {
	$file_exists = file_exists('assets/profpic/'.$username.'.png');
	$twig = twigloader('components');
	return $twig->render('profileimage.twig', ['data' => $username, 'file_exists' => $file_exists]);
}

function channelBackground($username) {
	$file_exists = file_exists('assets/backgrounds/'.$username.'.png');
	$twig = twigloader('components');
	return $twig->render('channelbackground.twig', ['data' => $username, 'file_exists' => $file_exists]);
}

function videoThumbnail($videodata) {
	$file_exists = file_exists('assets/thumb/'.$videodata.'.png');
	$twig = twigloader('components');
	return $twig->render('videothumbnail.twig', ['data' => $videodata, 'file_exists' => $file_exists]);
}

function browseVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('browsevideobox.twig', ['data' => $videodata]);
}

function smallVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('smallvideobox.twig', ['data' => $videodata]);
}

function browseChannelBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('browsechannelbox.twig', ['data' => $videodata]);
}

function videoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('videobox.twig', ['data' => $videodata]);
}

function icon($icon, $size) {
	$twig = twigloader('components');
	return $twig->render('icon.twig', ['icon' => $icon, 'size' => $size]);
}

function pagination($total, $current, $url, $nearbyPagesLimit = 4) {
	$twig = twigloader('components');
	return $twig->render('pagination.twig', ['total' => $total, 'current' => $current, 'url' => $url, 'nearbyPagesLimit' => $nearbyPagesLimit]);
}

function relativeTime($time) {
	$config = [
		'language' => __('\RelativeTime\Languages\English'),
		'separator' => ', ',
		'suffix' => true,
		'truncate' => 1,
	];

	$relativeTime = new \RelativeTime\RelativeTime($config);

	return $relativeTime->timeAgo($time);
}

function jsonDecode($str) {
	return json_decode($str);
}

function redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
}
