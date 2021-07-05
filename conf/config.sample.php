<?php
$host = '127.0.0.1';
$db   = 'squarebracket';
$user = '';
$pass = '';

$basepath = '/';

$ffmpegPath = '';
$ffprobePath = '';

$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

$webhook = ""; // Webhook URL for the new video webhook

$domain = 'http://squarebracket.local';
$isDebug = false; // DEV ONLY

// Add your own custom menu links here, or change the existing ones.
$menuLinks = [
	[
		'name' => "Discord",
		'url' => "https://discord.gg/uGWvcDpmZS",
		'icon' => 'discord',
	], [
		'name' => "Github",
		'url' => "https://github.com/chazizsquarebracket/squarebracket",
		'icon' => 'github',
	]
];
