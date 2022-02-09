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

function twigloader($subfolder = '', $customloader = null, $customenv = null) {
	global $userfields, $lpp, $tplCache, $tplNoCache, $log, $userdata, $theme, $pfpRoundness, $languages, $frontend, $menuLinks, $notificationCount, $hCaptchaSiteKey, $nonFunctionalShit, $pageVariable;

	if ($log) {
	$totalSubscribers = result("SELECT SUM(user) FROM subscriptions WHERE user = ?", [$userdata['id']]);
	$allUsers = query("SELECT $userfields s.* FROM subscriptions s JOIN users u ON s.user = u.id WHERE s.id = ?", [$userdata['id']]); // this line of code is absoultely broken, fix this. genuinely fix this. don't nag ictyfag though.
	} else {
	$totalSubscribers = 0;
	$allUsers = query("SELECT name, lastview FROM users ORDER BY lastview DESC LIMIT 10");
	}
	
	$doCache = ($tplNoCache ? false : $tplCache);
	//ugly hack to prevent reading templates from the wrong place
	chdir(__DIR__);
	chdir('../');
	if (!isset($customloader)) {
		$loader = new \Twig\Loader\FilesystemLoader('templates/' . $frontend . '/' . $subfolder);
	} else {
		$loader = $customloader();
	}
	
	if (!isset($customenv)) {
		$twig = new \Twig\Environment($loader, [
			'cache' => $doCache,
		]);
	} else {
		$twig = $customenv($loader, $doCache);
	}

	// why was this line of code not added
	$twig->addRuntimeLoader(new class implements RuntimeLoaderInterface {
		public function load($class) {
			if (MarkdownRuntime::class === $class) {
				return new MarkdownRuntime(new DefaultMarkdown());
			}
		}
	});
	
	$twig->addExtension(new SBExtension());
	$twig->addExtension(new MarkdownExtension());

	$twig->addGlobal('log', $log); //for forums
	$twig->addGlobal('menu_links', $menuLinks);
	$twig->addGlobal('userdata', $userdata);
	$twig->addGlobal('theme', $theme);
	$twig->addGlobal('pfpRoundness', $pfpRoundness);
	$twig->addGlobal('glob_languages', $languages);
	$twig->addGlobal("page_url", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
	$twig->addGlobal("domain", (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/");
	$twig->addGlobal('hcaptcha_sitekey', $hCaptchaSiteKey);
	$twig->addGlobal('glob_lpp', $lpp);
	$twig->addGlobal('notification_count', $notificationCount);
	$twig->addGlobal('showGuideWhatever', $nonFunctionalShit);
	$twig->addGlobal('page', $pageVariable);
	$twig->addGlobal('totalSubscribers', $totalSubscribers);
	$twig->addGlobal('allUsers', $allUsers);
	

	return $twig;
}

function jsonDecode($str) {
	return json_decode($str);
}

function error($errorCode, $message) {
	$twig = twigloader();
	echo $twig->render('_error.twig', ['err_message' => $message, 'err_code' => $errorCode]);
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

function videoLength($videodata) {
	$twig = twigloader('components');
	return $twig->render('videolength.twig', ['data' => $videodata]);
}

function browseVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('browsevideobox.twig', ['data' => $videodata]);
}

function smallVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('smallvideobox.twig', ['data' => $videodata]);
}

function verticalVideoBox($videodata) {
	$twig = twigloader('components');
	return $twig->render('verticalvideobox.twig', ['data' => $videodata]);
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

function icon_alt($icon, $size) {
	$twig = twigloader('components');
	return $twig->render('icon_alt.twig', ['icon' => $icon, 'size' => $size]);
}

function pagination($levels, $lpp, $url, $current) {
	$twig = twigloader('components');
	return $twig->render('pagination.twig', ['levels' => $levels, 'lpp' => $lpp, 'url' => $url, 'current' => $current]);
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