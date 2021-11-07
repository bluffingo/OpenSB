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
$isMaintenance = false;

$lpp = 20; //Fuck.

// TEMPLATE OPTIONS (redone 9/17/2021 by gr):
$useTemplate = "sbnext-finalium"; // check the templates folder for available options

// hCaptcha secret and sitekey,
// if these are empty, hCaptcha is disabled.
$hCaptchaSiteKey = '';
$hCaptchaSecret = '';

// Add your own custom menu links here, or change the existing ones.
// This is really only used in the current layout, not sure if
// sbNext should have them. I'm guessing no one clicked the links.
$menuLinks = [
	[
		'name' => "Github",
		'url' => "https://github.com/gr-sb/squarebracket",
		'icon' => 'github',
	]
];
