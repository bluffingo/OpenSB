<?php

namespace Orange;

if (!file_exists(dirname(__DIR__) . '/../vendor/autoload.php')) {
    die('<b>You are missing the required Composer packages. Please read the installing instructions in the README file.</b>');
}

if (!file_exists(dirname(__DIR__) . '/conf/config.php')) {
    die('<b>The configuration file could not be found. Please read the installing instructions in the README file.</b>');
}

require_once(dirname(__DIR__) . '/conf/config.php');

require_once(dirname(__DIR__) . '/../vendor/autoload.php'); //dogshit

// commented out because of SB_MEDIAWIKI
// global $host, $user, $pass, $db, $isQoboTV, $isMaintenance;

use GUMP;

foreach (glob(dirname(__DIR__) . "/interfaces/*.php") as $file) {
    require_once($file);
}

foreach (glob(dirname(__DIR__) . "/class/*.php") as $file) {
    require_once($file);
}

$orange = new \Orange\Orange($host, $user, $pass, $db);
$auth = new \Orange\Authentication($orange->getDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new \Orange\Profiler();
$gump = new GUMP('en');

if (!defined("SB_MEDIAWIKI")) {
    if ($isMaintenance) {
        $twig = new \Orange\Templating($orange);
        echo $twig->render("error.twig", [
            "error_title" => "Offline",
            "error_reason" => "The site is currently offline."
        ]);
        die();
    }

    $database = $orange->getDatabase();

    if ( $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [MiscFunctions::get_ip_address()])) {
        $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [MiscFunctions::get_ip_address()]));
        $twig = new \Orange\Templating($orange);
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
        $storage = new \Orange\BunnyStorage($orange);
    } else {
        $storage = new \Orange\LocalStorage($orange);
    }
}