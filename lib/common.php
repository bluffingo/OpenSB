<?php
if (!file_exists('conf/config.php')) {
	die('Fatal squareBracket Error: Please read the installing instructions in the README file.');
}

require('conf/config.php');
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
	if ($isDebug) {
		// todo: fix issue where font gets changed to browser's serif font when printing this string.
		printf('debug: logged in as %s', $currentUser['username']);
	}
} else {
	// put any default settings here as they get added
}
