<?php
// Change directory to squareBracket and include core squareBracket code.
chdir('../');
require_once('conf/config.php'); // include squareBracket config
require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}
require_once('lib/common.php');

// Change back to studio and include studio-specific code
chdir('studio/');
foreach (glob("lib/*.php") as $filename)
	require_once($filename);

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

if ($loggedIn) {
	$currentUser = fetch("SELECT * FROM users WHERE id = ?", [$id]);
	//printf('debug: logged in as %s', $currentUser['username']);
} else {
	// put any default settings here as they get added
}