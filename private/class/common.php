<?php

namespace squareBracket;
$releaseNumber = "rewrite-prototype";
$buildNumber = 0;
$versionNumber = $releaseNumber . "-" . str_pad($buildNumber, 3, "0", STR_PAD_LEFT);
$gitBranch = "code-rewrite";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");

if (!file_exists(dirname(__DIR__) . '/conf/config.php')) {
    die('<b>A configuration file could not be found. Please read the installing instructions in the README file.</b>');
}

require(dirname(__DIR__) . '/conf/config.php');

if ($isDebug and !isset($rawOutputRequired)) {
    // load profiler first
    require_once('profiler.php');
    $profiler = new Profiler();
}

require('../vendor/autoload.php'); //dogshit

foreach (glob(dirname(__DIR__) . "/class/*.php") as $file) {
    require_once($file);
}

// Holy shit! Classes!
$sql = new MySQL($host, $user, $pass, $db);

// user agent blocking shit
if (!empty($blockedUA) && isset($_SERVER['HTTP_USER_AGENT'])) {
    foreach ($blockedUA as $bl) {
        if (str_contains($_SERVER['HTTP_USER_AGENT'], $bl)) {
            http_response_code(403);
            echo '403';
            die();
        }
    }
}

if (isset($_COOKIE['frontend'])) {
    $frontendName = $_COOKIE['frontend'];
    $frontend = $_COOKIE['frontend'] . "-desktop";
    $frontendCommon = $_COOKIE['frontend'] . "-common";
    $mobileFrontend = $_COOKIE['frontend'] . "-mobile";
} else {
    $frontendName = (isset($useTemplate) ? $useTemplate : 'sbnext');
    $frontend = (isset($useTemplate) ? $useTemplate . "-desktop" : 'sbnext-desktop');
    $frontendCommon = (isset($useTemplate) ? $useTemplate . "-common" : 'sbnext-common');
    $mobileFrontend = (isset($useTemplate) ? $useTemplate . "-mobile" : 'sbnext-mobile');
}


/**
 * Returns true if it is executed from a cattleDog script.
 * cattleDog is an official collection of scripts designed
 * to migrate data onto a squareBracket instance. cattleDog
 * scripts are out of scope for squareBracket, which is why
 * they are not in the repository.
 */
function isCattleDog()
{
    global $_SESSION;
    if (isCli()) {
        return isset($_SESSION['isCattleDog']);
    }
}

// cattleDog's verify.php fucks up if this isn't done.
if (!isCattleDog()) {
    $lang = new Lang(dirname(__DIR__) . "/lang/" . ($_COOKIE['language'] ?? 'en-US') . ".json");

    $userfields = Users::userfields();
    $videofields = Videos::videofields();
}

if ($isMaintenance && !isCli()) {
    error(403, "This instance of squareBracket is currently offline.");
}

// Cookie auth
if (isset($_COOKIE['SBTOKEN'])) {
    $id = $sql->result("SELECT id FROM users WHERE token = ?", [$_COOKIE['SBTOKEN']]);

    if ($id) {
        // Valid cookie, logged in
        $log = true;
    } else {
        // Invalid cookie, not logged in
        $log = false;
    }
} else {
    // No cookie, not logged in
    $log = false;
}

// Theme selector stuff
if (isset($_COOKIE['theme'])) {
    $theme = $_COOKIE['theme'];
} else {
    // No cookie, default to the default theme
    $theme = 'default';
}

// Rounded pfp shit (suggested by sks2002)
if (isset($_COOKIE['profilepicture'])) {
    $pfpRoundness = $_COOKIE['profilepicture'];
} else {
    // No cookie, default to circle
    $pfpRoundness = 'default';
}

if ($log) {
    $userdata = $sql->fetch("SELECT * FROM users WHERE id = ?", [$id]);
    $notificationCount = $sql->result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);
} else {
    $userdata['powerlevel'] = 1;
}

$userdata['timezone'] = 'America/New York';
