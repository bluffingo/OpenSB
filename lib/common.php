<?php
if (!file_exists('conf/config.php')) {
	die('Fatal squareBracket Error: Please read the installing instructions in the README file.');
}

require('conf/config.php');

if ($isDebug) {
	// load profiler first
	require_once('lib/profiler.php');
	$profiler = new Profiler();
}

require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
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

if ($loggedIn) {
	$currentUser = fetch("SELECT * FROM users WHERE id = ?", [$id]);
}

$lang = new Lang(sprintf("lib/lang/".(isset($currentUser['language']) ? $currentUser['language'] : 'en_US').".json"));