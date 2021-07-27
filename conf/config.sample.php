<?php
//SQL DB
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

$testNewLayout = false; // DEV ONLY

// hCaptcha secret and sitekey,
// if these are empty, hCaptcha is disabled.
$hCaptchaSiteKey = '';
$hCaptchaSecret = '';

$sbNext = false; // DEV ONLY (for now), intended to make sbNext development easier.

// Add your own custom menu links here, or change the existing ones.
// This is really only used in the Bootstrap layout, not sure if
// sbNext should have them. I'm guessing no one clicked the links.
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
