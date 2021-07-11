<?php
if (!file_exists('conf/config.php')) {
	die('A configuration file could not be found. Please read the installing instructions in the README file.');
}

require('conf/config.php');

if ($isDebug and !isset($rawOutputRequired)) {
	// load profiler first
	require_once('lib/profiler.php');
	$profiler = new Profiler();
}

require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}

function _twigloader($subfolder = '') {
	$twig = twigloader($subfolder, function () use ($subfolder) {
		return new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	}, function ($loader, $doCache) {

		return new \Twig\Environment($loader, [
			'cache' => ($doCache ? "../".$doCache : $doCache),
		]);
	});

	return $twig;
}

$userfields = userfields();

// Cookie auth
if (isset($_COOKIE['SBTOKEN'])) {
	$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE['SBTOKEN']]);

	if ($id) {
		// Valid cookie, logged in
		$loggedIn = true;
	} else {
		// Invalid cookie, not logged in
		$loggedIn = false;
	}
} else {
	// No cookie, not logged in
	$loggedIn = false;
}

// Theme selector stuff
if (isset($_COOKIE['theme'])) {
	$theme = $_COOKIE['theme'];
} else {
	// No cookie, default to the default theme
	$theme = 'default';
}

$lang = new Lang(sprintf("lib/lang/".(isset($currentUser['language']) ? $currentUser['language'] : 'en_US').".json"));

if ($sbNext) {
    $frontend = 'new';
} else {
    $frontend = (isset($_GET['frontend']) ? $_GET['frontend'] : 'default');
}

if ($loggedIn) {
	$currentUser = fetch("SELECT * FROM users WHERE id = ?", [$id]);
	// Intended for testing sbNext on the production server (squarebracket.veselcraft.ru)
	// without forcing everyone to use some incomplete crap. -gr 7/11/21
	if ($currentUser['username'] == "squareBracket") {
		$frontend = 'new';
	} else {
    $frontend = (isset($_GET['frontend']) ? $_GET['frontend'] : 'default');
	}
}
