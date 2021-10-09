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

//Vitre functions because the only other place I could put them would cause the script to crash.
//These may be Blockland-centric, and will need to be hacked a FUCKTON in case we make a proper Vitre client.
//-GR 10/6/2021

//Spawns a Message Box
function v_messageBox($client, $title, $text) {
socket_write($client, json_encode(array('type' => "MessageBox", 'title' => $title, 'text' => $text))."\n");
}

//echos debug-only shit, much more useful for a CLI than a web page so this is vitre server-only.
if ($isDebug)
{
    function v_debugEcho($text)
    {
        echo "DEBUG: ".$text."\n";
    }
}
else
{
    function v_debugEcho($text)
    {
        return;
    }
}