<?php
$acmlm = true;

// List of smilies
$smilies = [];

// Change directory to principia-web and include core principia-web code.
chdir('../');
require_once('conf/config.php'); // include principia-web config
require_once('vendor/autoload.php');
require_once('lib/common.php');

// Change back to forum and include forum-specific code
chdir('forum/');
foreach (glob("lib/*.php") as $filename)
	require_once($filename);

preloadGroupData();

$logpermset = [];

if ($log) {
	loadUserPermset();
} else {
	loadGuestPermset();

	$userdata['id'] = 0;
}

// todo
$userdata['dateformat'] = "Y-m-d";
$userdata['timeformat'] = "H:i";
$userdata['ppp'] = 20;
$userdata['tpp'] = 20;
$dateformat = $userdata['dateformat'].' '.$userdata['timeformat'];
