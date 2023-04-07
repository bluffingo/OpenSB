<?php

namespace openSB;
//SQL DB
$host = '127.0.0.1';
$db = 'squarebracket';
$user = '';
$pass = '';

$basepath = '/';

$ffmpegPath = '';
$ffprobePath = '';

$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

$domain = 'https://squarebracket.local';
$isDebug = false; // DEV ONLY
$isMaintenance = false;

$paginationLimit = 20; //Pagination limit.

// TEMPLATE OPTIONS (redone 9/17/2021 by gr):
$useTemplate = "sbnext"; // check the templates folder for available options, we recommend "sbnext" as others are experimental shit that always ends up getting cut.

// Branding
$branding = [
	"name" => "an openSB Instance",
	"assets_location" => "/assets/placeholder",
	"css_override" => false, // change to location of custom finalium overrides.
];

$isQoboTV = false; // this lowers the video quality to what is on production, blame the bunnycdn php library not fucking working. -grkb 4/6/2023