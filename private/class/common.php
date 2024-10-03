<?php

namespace OpenSB;

if (version_compare(PHP_VERSION, '8.2.0') <= 0) {
    die('<strong>OpenSB is not compatible with your PHP version. OpenSB supports PHP 8.2 or newer.</strong>');
}

if (!file_exists(SB_VENDOR_PATH . '/autoload.php')) {
    die('<strong>You are missing the required Composer packages. Please read the installing instructions in the README file.</strong>');
}

// yes. you can call me stupid for this. but this is done because i don't want the new code to use the old shitty
// configs. -chaziz 7/31/2024
if (!file_exists(SB_PRIVATE_PATH . '/config/config.php')) {
    die('<strong>The configuration file could not be found. Please read the installing instructions in the README file.</strong>');
}

$config = include_once(SB_PRIVATE_PATH . '/config/config.php');

require_once(SB_VENDOR_PATH . '/autoload.php');

use OpenSB\class\Core\Authentication;
use OpenSB\class\Core\Localization;
use OpenSB\class\Core\Profiler;
use OpenSB\class\Core\CoreClasses;
use OpenSB\class\Core\Storage;
use OpenSB\class\Core\Templating;
use OpenSB\class\Core\Utilities;

// please use apache/nginx for production stuff.
if (php_sapi_name() == "cli-server") {
    define("SB_PHP_BUILTINSERVER", true);
} else {
    define("SB_PHP_BUILTINSERVER", false);
}

if (session_status() === PHP_SESSION_NONE) {
    session_name("sb_session");

    $is_secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $is_secure,
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    session_start([
        "cookie_lifetime" => 1209600,
        "gc_maxlifetime" => 1209600,
    ]);
}

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    if (file_exists(SB_PRIVATE_PATH . "/class/$class_name.php")) {
        require SB_PRIVATE_PATH . "/class/$class_name.php";
    }
});

// since opensb orange is shitty code and uses global everywhere. convert new config variables
// to old global config variables to avoid fucking around with the legacy orange code.
$host = $config["mysql"]["host"];
$db = $config["mysql"]["database"];
$user = $config["mysql"]["username"];
$pass = $config["mysql"]["password"];

$captcha = $config["captcha"];

if ($config["site"] == "squarebracket_chaziz") {
    $isChazizSB = true;
} else {
    $isChazizSB = false;
    if ($config["site"] != "squarebracket") {
        trigger_error("This variable should be set to either squarebracket or squarebracket_chaziz.",
            E_USER_ERROR);
    }
}

if ($config["mode"] == "DEV") {
    $isDebug = true;
} else {
    $isDebug = false;
}

if ($config["cache"]) {
    $enableCache = true;
} else {
    $enableCache = false;
}

if ($config["maintenance"]) {
    $isMaintenance = true;
} else {
    $isMaintenance = false;
}

// Bunny settings which are only used if $isChazizSB is true
$bunnySettings = [
    "streamApi" => $config["bunny_settings"]["stream_api"],
    "streamLibrary" => $config["bunny_settings"]["stream_library"],
    "streamHostname" => $config["bunny_settings"]["stream_hostname"],
    "storageApi" => $config["bunny_settings"]["storage_api"],
    "storageZone" => $config["bunny_settings"]["storage_zone"],
    "pullZone" => $config["bunny_settings"]["pull_zone"],
];

// Branding settings
$branding = [
    "name" => $config["branding"]["name"],
    "assets_location" => $config["branding"]["assets"],
];

// TODO: port these to feature flags or settings that can be changed in-site.
$disableRegistration = false;
$disableUploading = false;
$disableWritingJournals = false;
$enableInviteKeys = false;

// now initialize the orange classes
$orange = new CoreClasses($host, $user, $pass, $db);
$database = $orange->getDatabase();
$auth = new Authentication($database);
$profiler = new Profiler();
$twig = new Templating($orange);
$localization = new Localization();

// automatic stuff
// this should probably have a cooldown or something i don't fucking know

// automatically ban accounts linked to banned ips.
$ipBannedUsers = $database->fetchArray($database->query("SELECT * from ipbans"));
foreach ($ipBannedUsers as $ipBannedUser) {
    $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT id, name FROM users WHERE ip LIKE ?", [$ipBannedUser["ip"]]));
    foreach ($usersAssociatedWithIP as $ipBannedUser2) { // i can't really name variables that well
        if (!$database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$ipBannedUser2["id"]])) {
            $database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                [$ipBannedUser2["id"], "Automatically done by OpenSB", time()]);
        }
    }
}

$storage = new Storage($orange->getDatabase(), $isChazizSB, $bunnySettings);

// and i am being serious with this. -chaziz 8/7/2024
if (!file_exists(SB_GIT_PATH)) {
    // to clarify with this: if you are downloading the opensb source code from that fucking shitty github download
    // button. you are effectively making it hard for your instance to be able to be updated with newer upstream code
    // (via a git pull), if you do not know how to use git, please do not bother setting up opensb. -chaziz 8/31/2024
    echo $twig->render("error.twig", [
        "error_title" => "Critical error",
        "error_reason" => "Please initialize OpenSB using git clone instead of downloading it straight from GitHub,
             especially if you want to keep your instance up to date."
    ]);
    die();
}

if ($ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [Utilities::getIpAddress()])) {
    $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [Utilities::getIpAddress()]));
    echo $twig->render("ip_banned.twig", [
        "data" => $ipban,
        "users" => $usersAssociatedWithIP,
    ]);
    die();
}

if ($isMaintenance && !SB_PHP_BUILTINSERVER) {
    echo $twig->render("error.twig", [
        "error_title" => "Offline",
        "error_reason" => "This site is currently offline."
    ]);
    die();
}