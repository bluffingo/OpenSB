<?php
namespace squareBracket;
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
$useTemplate = "sbnext"; // check the templates folder for available options