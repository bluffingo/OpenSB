<?php
namespace squareBracket;
$versionNumber = "beta-2.1.0r1";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");

if (!file_exists('conf/config.php')) {
	die('<center><b>A configuration file could not be found. Please read the installing instructions in the README file.</b></center>');
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

if (isset($_COOKIE['frontend'])) {
	$frontend = $_COOKIE['frontend']."-desktop";
	$frontendCommon = $_COOKIE['frontend']."-common";
	$mobileFrontend = $_COOKIE['frontend']."-mobile";
} else {
	$frontend = (isset($useTemplate) ? $useTemplate."-desktop" : 'sbnext-desktop');
	$frontendCommon = (isset($useTemplate) ? $useTemplate."-common" : 'sbnext-common');
	$mobileFrontend = (isset($useTemplate) ? $useTemplate."-mobile" : 'sbnext-mobile');
}

$lang = new Lang(sprintf("lib/lang/".(isset($_COOKIE['language']) ? $_COOKIE['language'] : 'en-US').".json"));

if ($isMaintenance && !isCli()) {
	error(403, "This instance of squareBracket is currently offline.");
}

$userfields = userfields();
$videofields = videofields();

// Cookie auth
if (isset($_COOKIE['SBTOKEN'])) {
	$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE['SBTOKEN']]);

	if ($id) {
		// Valid cookie, logged in
		$log = true;
	} else {
		// Invalid cookie, not logged in
		$log = false;
	}
} else {
	// No cookie, not logged in
	$log = false;
}

// Theme selector stuff
if (isset($_COOKIE['theme'])) {
	$theme = $_COOKIE['theme'];
} else {
	// No cookie, default to the default theme
	$theme = 'default';
}

// Rounded pfp shit (suggested by sks2002)
if (isset($_COOKIE['profilepicture'])) {
	$pfpRoundness = $_COOKIE['profilepicture'];
} else {
	// No cookie, default to circle
	$pfpRoundness = 'default';
}

if ($log) {
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$id]);
	$notificationCount = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);
} else {
	$userdata['powerlevel'] = 1;
}

$userdata['timezone'] = 'America/New York';
