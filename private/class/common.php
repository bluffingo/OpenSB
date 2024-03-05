<?php

namespace OpenSB;

if (version_compare(PHP_VERSION, '8.2.0') <= 0) {
    die('<strong>OpenSB is not compatible with your PHP version. OpenSB supports PHP 8.2 or newer.</strong>');
}

if (!file_exists(SB_VENDOR_PATH . '/autoload.php')) {
    die('<strong>You are missing the required Composer packages. Please read the installing instructions in the README file.</strong>');
}

if (!file_exists(SB_PRIVATE_PATH . '/conf/config.php')) {
    die('<strong>The configuration file could not be found. Please read the installing instructions in the README file.</strong>');
}

require_once(SB_PRIVATE_PATH . '/conf/config.php');

require_once(SB_VENDOR_PATH . '/autoload.php');

global $host, $user, $pass, $db, $isQoboTV, $useMuffinCDN;

use GUMP;
use Orange\MuffinStorage;
use SquareBracket\Authentication;
use SquareBracket\BunnyStorage;
use SquareBracket\LocalStorage;
use SquareBracket\SquareBracket;
use SquareBracket\SquareBracketException;
use SquareBracket\Profiler;
use SquareBracket\Templating;
use SquareBracket\Utilities;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    if (file_exists(SB_PRIVATE_PATH . "/class/$class_name.php")) {
        require SB_PRIVATE_PATH . "/class/$class_name.php";
    }
});

$orange = new SquareBracket($host, $user, $pass, $db);
$auth = new Authentication($orange->getDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new Profiler();
$gump = new GUMP('en');

if ($orange->getSettings()->getMaintenanceMode()) {
    $twig = new Templating($orange);
    echo $twig->render("error.twig", [
        "error_title" => "Offline",
        "error_reason" => "The site is currently offline."
    ]);
    die();
}

$database = $orange->getDatabase();

if ( $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [Utilities::get_ip_address()])) {
    $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [Utilities::get_ip_address()]));
    $twig = new Templating($orange);
    echo $twig->render("ip_banned.twig", [
        "data" => $ipban,
        "users" => $usersAssociatedWithIP,
    ]);
    die();
}

// automatic stuff
// this should probably have a cooldown or something i don't fucking know

// ban users who are ip banned
$ipBannedUsers = $database->fetchArray($database->query("SELECT * from ipbans"));

foreach ($ipBannedUsers as $ipBannedUser) {
    $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT id FROM users WHERE ip LIKE ?", [$ipBannedUser["ip"]]));
    foreach ($usersAssociatedWithIP as $ipBannedUser2) { // i can't really name variables that well
        if (!$database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$ipBannedUser2["id"]])) {
            $database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                [$ipBannedUser2["id"], "Automatically done by OpenSB", time()]);
        }
    }
}

if ($isQoboTV) {
    if ($useMuffinCDN) {
        throw new SquareBracketException("The MuffinCDN interface is no longer available.");
    } else {
        $storage = new BunnyStorage($orange);
    }
} else {
    if ($useMuffinCDN) {
        throw new SquareBracketException("The MuffinCDN interface can only be used in Qobo mode");
    } else {
        $storage = new LocalStorage($orange);
    }
}