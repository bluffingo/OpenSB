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

$paginationLimit = 20; //Pagination limit.

// Branding
$branding = [
    "name" => "OpenSB Development",
	"assets_location" => "/assets/placeholder",
];

$isBluffingoSB = false; // this makes opensb use bunnycdn for storage.

// only used if $isBluffingoSB is true
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

$debugLogging = false;

$enableFederatedStuff = false;

// this should match with the dynamic alias' location.
// for a production environment, it may be preferred to
// not have the dynamic folder be inside the opensb directory.
$dynamicFolderLocation = "insert/dynamic/location/here";