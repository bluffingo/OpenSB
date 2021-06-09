<?php
if (!file_exists('conf/config.php')) {
	die('Fatal error: Please read the squareBracket installing instructions in the README file.');
}

require_once('conf/config.php');

chdir('../');
require_once('vendor/autoload.php');
require_once('lib/mysql.php');

chdir('gamerappa/');
foreach (glob("lib/*.php") as $filename)
    require_once($filename);

echo("test");

if ($isDebug and !isset($rawOutputRequired)) {
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