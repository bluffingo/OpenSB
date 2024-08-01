<?php

namespace OpenSB;

global $host, $user, $pass, $db, $isChazizSB, $debugLogging, $isMaintenance, $bunnySettings, $runNewShit, $dynamicFolderLocation;

if (version_compare(PHP_VERSION, '8.2.0') <= 0) {
    die('<strong>OpenSB is not compatible with your PHP version. OpenSB supports PHP 8.2 or newer.</strong>');
}

if (!file_exists(SB_VENDOR_PATH . '/autoload.php')) {
    die('<strong>You are missing the required Composer packages. Please read the installing instructions in the README file.</strong>');
}

// yes. you can call me stupid for this. but this is done because i don't want the new code to use the old shitty
// configs. -chaziz 7/31/2024
if ($runNewShit) {
    if (!file_exists(SB_PRIVATE_PATH . '/config/config.php')) {
        die('<strong>The NEW configuration file could not be found.</strong>');
    }
} else {
    if (!file_exists(SB_PRIVATE_PATH . '/conf/config.php')) {
        die('<strong>The configuration file could not be found. Please read the installing instructions in the README file.</strong>');
    }
}

if (!$runNewShit) {
    require_once(SB_PRIVATE_PATH . '/conf/config.php');

    if(!file_exists($dynamicFolderLocation)) {
        die('<strong>The dynamic folder can not be found. Please read the installing instructions in the README file.</strong>');
    }
}

require_once(SB_VENDOR_PATH . '/autoload.php');

use SquareBracket\Authentication;
use SquareBracket\Localization;
use SquareBracket\Profiler;
use SquareBracket\SquareBracket;
use SquareBracket\Storage;
use SquareBracket\Templating;
use SquareBracket\Utilities;

if(!$runNewShit) {
// please use apache/nginx for production stuff.
if (php_sapi_name() == "cli-server") {
    define("SB_PHP_BUILTINSERVER", true);
} else {
    define("SB_PHP_BUILTINSERVER", false);
}
    spl_autoload_register(function ($class_name) {
        $class_name = str_replace('\\', '/', $class_name);
        if (file_exists(SB_PRIVATE_PATH . "/class/$class_name.php")) {
            require SB_PRIVATE_PATH . "/class/$class_name.php";
        }
    });

    function sb_debug_output($string)
    {
        if (SB_PHP_BUILTINSERVER) {
            $time = date("Y-m-d H:i:s");

            $output = "[OPENSB {$time}] {$string}";
            file_put_contents("php://stdout", $output . PHP_EOL);
        }
    }

    if ($debugLogging && function_exists('getallheaders')) {
        // Get all headers and requests sent to this server
        $headers = getallheaders();
        $postData = $_POST;
        $getData = $_GET;
        $filesData = $_FILES;
        $body = json_decode(file_get_contents("php://input"), true);
        $requestData = $_REQUEST;
        $serverData = $_SERVER;

        // Get the type of request - used in the log filename
        // If there's nothing, use the URL as an alternative.
        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($urlPath, '/'));

        if (isset($body["type"])) {
            $type = $body["type"];
        } else {
            if (!empty($pathParts)) {
                if ($pathParts[0] === "") {
                    $type = "index";
                } else if (count($pathParts) >= 2) {
                    $lastTwoParts = array_slice($pathParts, -2);
                    $type = implode(" ", $lastTwoParts);
                } else {
                    $type = $pathParts[0];
                }
            } else {
                $type = "unknown";
            }
        }

        // Unix timestamp, whatever.
        $timestamp = time();

        // Filename for the log
        $filename = "{$timestamp}{$type}.txt";

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
    $database = $orange->getDatabase();
    $auth = new Authentication($database, $_COOKIE['SBTOKEN'] ?? null);
    $profiler = new Profiler();
    $twig = new Templating($orange);
    $localization = new Localization();

// automatic stuff
// this should probably have a cooldown or something i don't fucking know

// this can be easily bypassed but my paranoia wants me to implement this -Chaziz 4/8/2024
    if (isset($_SERVER['HTTP_REFERER'])) {
        $blacklistedReferers = $database->fetch("SELECT url from blacklisted_referer where url = ?", [$_SERVER['HTTP_REFERER']]);
        if ($blacklistedReferers) {
            $alreadyIpBanned = $database->fetch("SELECT * from ipbans where ip = ?", [Utilities::get_ip_address()]);
            if (!$alreadyIpBanned) {
                sb_debug_output("Automatically banning IP " . Utilities::get_ip_address());
                $database->query("INSERT INTO ipbans (ip, reason, time) VALUES (?,?,?)",
                    [Utilities::get_ip_address(), "[Automatically done by OpenSB] Referer is from blacklisted website "
                        . $_SERVER['HTTP_REFERER'], time()]);
            }
        }
    }

// automatically ban accounts linked to banned ips.
    $ipBannedUsers = $database->fetchArray($database->query("SELECT * from ipbans"));
    foreach ($ipBannedUsers as $ipBannedUser) {
        $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT id, name FROM users WHERE ip LIKE ?", [$ipBannedUser["ip"]]));
        foreach ($usersAssociatedWithIP as $ipBannedUser2) { // i can't really name variables that well
            if (!$database->fetch("SELECT b.userid FROM bans b WHERE b.userid = ?", [$ipBannedUser2["id"]])) {
                sb_debug_output("Automatically banning user {$ipBannedUser2["name"]}");
                $database->query("INSERT INTO bans (userid, reason, time) VALUES (?,?,?)",
                    [$ipBannedUser2["id"], "Automatically done by OpenSB", time()]);
            }
        }
    }

    $storage = new Storage($orange->getDatabase(), $isChazizSB, $bunnySettings);

    if (!file_exists(SB_GIT_PATH)) {
        echo $twig->render("error.twig", [
            "error_title" => "Critical error",
            "error_reason" => "Please initialize OpenSB using git clone instead of downloading it straight from GitHub."
        ]);
        die();
    }

    if ($ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [Utilities::get_ip_address()])) {
        $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [Utilities::get_ip_address()]));
        echo $twig->render("ip_banned.twig", [
            "data" => $ipban,
            "users" => $usersAssociatedWithIP,
        ]);
        die();
    }

    if ($isMaintenance && !SB_PHP_BUILTINSERVER) {
        echo $twig->render("error.twig", [
            "error_title" => "Offline",
            "error_reason" => "The site is currently offline."
        ]);
        die();
    }
}