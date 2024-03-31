<?php

namespace OpenSB;

global $debugLogging;
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

global $host, $user, $pass, $db, $isQoboTV;

use Core\Authentication;
use Core\Utilities as UtilitiesAlias;
use SquareBracket\BunnyStorage;
use SquareBracket\LocalStorage;
use SquareBracket\Profiler;
use SquareBracket\SquareBracket;
use SquareBracket\Templating;

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', '/', $class_name);
    if (file_exists(SB_PRIVATE_PATH . "/class/$class_name.php")) {
        require SB_PRIVATE_PATH . "/class/$class_name.php";
    }
});

if ($debugLogging) {
    // Get all headers and requests sent to this server
    $headers     = getallheaders();
    $postData    = $_POST;
    $getData     = $_GET;
    $filesData   = $_FILES;
    $body        = json_decode(file_get_contents("php://input"), true);
    $requestData = $_REQUEST;
    $serverData  = $_SERVER;

    // Get the type of request - used in the log filename
    $type = isset($body["type"]) ? " " . $body["type"] : "";

    // Unix timestamp, whatever.
    $timestamp = time();

    // Filename for the log
    $filename  = "{$timestamp}{$type}.txt";

    // Save headers and request data to the timestamped file in the logs directory
    $log_path = SB_PRIVATE_PATH . "/logs";

    if (!is_dir($log_path)) {
        mkdir($log_path);
    }

// Generate the log content
    $logContent =
        "Headers:     \n" . print_r($headers, true) . "\n\n" .
        "Body Data:   \n" . print_r($body, true) . "\n\n" .
        "POST Data:   \n" . print_r($postData, true) . "\n\n" .
        "GET Data:    \n" . print_r($getData, true) . "\n\n" .
        "Files Data:  \n" . print_r($filesData, true) . "\n\n" .
        "Request Data:\n" . print_r($requestData, true) . "\n\n" .
        "Server Data: \n" . print_r($serverData, true) . "\n\n";

// Write the log content to the file
    file_put_contents($log_path . "/{$filename}", $logContent);
}

$orange = new SquareBracket($host, $user, $pass, $db);
$auth = new Authentication($orange->getDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new Profiler();

if ($orange->getSettings()->getMaintenanceMode()) {
    $twig = new Templating($orange);
    echo $twig->render("error.twig", [
        "error_title" => "Offline",
        "error_reason" => "The site is currently offline."
    ]);
    die();
}

$database = $orange->getDatabase();

if ( $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [UtilitiesAlias::get_ip_address()])) {
    $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [UtilitiesAlias::get_ip_address()]));
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
    $storage = new BunnyStorage($orange);
} else {
    $storage = new LocalStorage($orange);
}