<?php
header("Access-Control-Allow-Origin: *");

if (!file_exists('conf/config.php')) {
	die('<center><b>A configuration file could not be found. Please read the installing instructions in the README file.</b></center>');
}

require('conf/config.php');

// todo: make this load a html page
if ($isMaintenance) {
	die('<center><b>squareBracket is currently offline for maintenance and upgrades.</b></center>');
}

if ($isDebug and !isset($rawOutputRequired)) {
	// load profiler first
	require_once('lib/profiler.php');
	$profiler = new Profiler();
}

require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}

// Makes incomplete unready features not available on production (aka squarebracket.veselcraft.ru)
function notReady() {
	http_response_code(403);
	die(__("This feature is not ready for production.")); 
}

function accessDenied() {
	http_response_code(403);
	die(__("Access Denied"));
}

if(!isset($acmlm)) {
$userfields = userfields();
}

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
	$theme = 'finalium';
}

if ($oldTemplateSwitching) {
	$frontend = (isset($_GET['frontend']) ? $_GET['frontend'] : 'default');
} else {
	$frontend = (isset($useTemplate) ? $useTemplate : 'default');
}

if ($loggedIn) {
	query("UPDATE users SET lastview = ? WHERE id = ?", [time(), $id]);
	$currentUser = fetch("SELECT * FROM users WHERE id = ?", [$id]);
}

$lang = new Lang(sprintf("lib/lang/".(isset($currentUser['language']) ? $currentUser['language'] : 'en-US').".json"));