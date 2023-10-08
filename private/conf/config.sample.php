<?php

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

// TEMPLATE OPTIONS
$useTemplate = "sbnext"; // Template used by pages yet to be migrated onto Betty.
$bettyTemplate = "qobo"; // Template used by pages based on Betty.

// Branding
$branding = [
    "name" => "a BettySB Instance",
	"assets_location" => "/assets/placeholder",
];

$isQoboTV = false; // if enabled, storage is switched to use bunnycdn. this is for a constraint regarding qobo.

// only used if $isQoboTV is true
$bunnySettings = [
	"streamApi" => "stream api key",
	"streamLibrary" => 12345,
	"streamHostname" => "[stream hostname].b-cdn.net",
	"storageApi" => "storage api key",
	"storageZone" => "storage zone name",
	"pullZone" => "[pull zone].b-cdn.net",
];

$disableUploading = false;

$googleAdsClient = false;