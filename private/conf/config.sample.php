<?php

//SQL DB
$host = '127.0.0.1';
$db = 'opensb';
$user = '';
$pass = '';

$basepath = '/';

$ffmpegPath = '';
$ffprobePath = '';

// these probably don't work
$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

$domain = 'example.com';
$isDebug = false; // DEV ONLY
$isMaintenance = false;

$debugLogging = false;

$paginationLimit = 20; //Pagination limit.

// TEMPLATE OPTIONS
$defaultTemplate = "biscuit";

// Branding
$branding = [
    "name" => "OpenSB Development",
	"assets_location" => "/assets/placeholder",
];

$isChazizSB = false; // this makes opensb use bunnycdn for storage.

// only used if $isChazizSB is true
// not everything here is actually used.
$bunnySettings = [
	"streamApi" => "stream api key",
	"streamLibrary" => 12345,
	"streamHostname" => "[stream hostname].b-cdn.net",
	"storageApi" => "storage api key",
	"storageZone" => "storage zone name",
	"pullZone" => "[pull zone].b-cdn.net",
];

$disableRegistration = false;
$disableUploading = false;
$disableWritingJournals = false;

$enableChat = false;
$enableInviteKeys = false;