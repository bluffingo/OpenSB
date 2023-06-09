<?php

namespace openSB;

global $googleTag, $googleAPI;

use Betty\BunnyStorage;
use Betty\LocalStorage;
use Br33f\Ga4\MeasurementProtocol\Dto\Request\BaseRequest;
use Br33f\Ga4\MeasurementProtocol\Service;

// we need this at the top, or else version numbers won't work.
require_once(dirname(__DIR__) . "/class/version.php");

if (!file_exists(dirname(__DIR__) . '/conf/config.php')) {
    die('<b>A configuration file could not be found. Please read the installing instructions in the README file.</b>');
}

require_once(dirname(__DIR__) . '/conf/config.php');

require_once(dirname(__DIR__) . '/../vendor/autoload.php'); //dogshit

// aaaa psr-4 autoload!!! - rgb

// load the betty stuff first
require_once(dirname(__DIR__) . "/../betty/common.php");

// and then the opensb stuff
foreach (glob(dirname(__DIR__) . "/class/*.php") as $file) {
    require_once($file);
}

$sql = $betty->getBettyDatabase();

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
    $frontendName = ($useTemplate ?? 'sbnext');
    $frontend = (isset($useTemplate) ? $useTemplate . "-desktop" : 'sbnext-desktop');
    $frontendCommon = (isset($useTemplate) ? $useTemplate . "-common" : 'sbnext-common');
    $mobileFrontend = (isset($useTemplate) ? $useTemplate . "-mobile" : 'sbnext-mobile');
}

// cattleDog's verify.php fucks up if this isn't done.
if (!isset($_SESSION['isCattleDog'])) {
	$accountfields = "id, name, email, customcolor, title, about, powerlevel, joined, lastview";
    $userfields = Users::userfields();
    $videofields = "v.id, v.video_id, v.title, v.description, v.time, v.post_type, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, (SELECT COUNT(*) FROM comments WHERE id = v.video_id) AS comments, (SELECT COUNT(*) FROM favorites WHERE video_id = v.video_id) AS favorites, (SELECT COUNT(*) FROM favorites WHERE video_id = v.video_id) AS favorites, v.videolength, v.category_id, v.author";
}

if ($isMaintenance && !isCli()) {
    error(403, "This openSB instance is currently offline.");
} else {
    $ipban = $sql->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [getUserIpAddr()]);
    if ($ipban) {
        error(403, "IP banned - " . ($ipban['reason'] ?? "No reason."));
        die();
    }
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
    $userdata = $sql->fetch("SELECT $accountfields FROM users WHERE id = ?", [$id]);
    $notificationCount = $sql->result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);
    $userbandata = $sql->fetch("SELECT * FROM bans WHERE userid = ?", [$id]);
} else {
    $userdata['powerlevel'] = 1;
}

function navigationList() {
	return [
		"browse" => [
			"name" => __("Browse"),
			"icon" => "video",
			"url" => "/browse.php",
			"htmlid" => "browse",
		],
	];
}

function randstr($len, $charset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-") {
    return substr(str_shuffle($charset),0,$len);
}

$userdata['timezone'] = 'America/New York';

if ($googleAPI) {
    $session = $_COOKIE['_ga'] ?? $_COOKIE['_gid'] ?? $_COOKIE['SBTOKEN'] ?? randstr(32); // THIS IS STUPID SHIT AND WILL BREAK

// FIXME: MOVE THIS TO BETTY
    $ga = new Service($googleAPI);
    $ga->setMeasurementId($googleTag);

// Create base request
    $baseRequest = new BaseRequest();
    $baseRequest->setClientId($session);
}