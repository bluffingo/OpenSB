<?php

namespace Orange;

if (!file_exists(dirname(__DIR__) . '/../vendor/autoload.php')) {
    die('<b>You are missing the required Composer packages. Please read the installing instructions in the README file.</b>');
}

if (!file_exists(dirname(__DIR__) . '/conf/config.php')) {
    die('<b>A configuration file could not be found. Please read the installing instructions in the README file.</b>');
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

/**
 * @since Orange 1.0
 */
class Orange {
    private \Orange\Database $database;
    private string $version;
    public array $options;

    public function __construct($host, $user, $pass, $db) {
        if (!defined( 'SB_MEDIAWIKI' )) {
            session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

            $this->options = [];
            if (isset($_COOKIE["SBOPTIONS"])) {
                $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
            }

            // should not be enabled on qobo.tv
            if ($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1") {
                $this->options["development"] = true;
            }

            $this->version = MiscFunctions::getSquareBracketVersion();
        }

        try {
            $this->database = new \Orange\Database($host, $user, $pass, $db);
        } catch (OrangeException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database for other Betty classes to use.
     *
     * @since Orange 1.0
     *
     * @return Database
     */
    public function getBettyDatabase(): \Orange\Database {
        return $this->database;
    }

    /**
     * Returns Betty's version number.
     *
     * @since Orange 1.0
     *
     * @return string
     */
    public function getBettyVersion(): string
    {
        return $this->version;
    }

    /**
     * Returns the user's local settings.
     *
     * @since Orange 1.0
     *
     * @return array
     */
    public function getLocalOptions(): array
    {
        return $this->options;
    }

    /**
     * Notifies the user, VidLii-style.
     *
     * Not to be confused with NotifyUser.
     *
     * @since Orange 1.0
     */
    public function Notification($message, $redirect, $color = "danger")
    {
        $_SESSION["notif_message"] = $message;
        $_SESSION["notif_color"] = $color;

        if ($redirect) {
            header(sprintf('Location: %s', $redirect));
            die();
        }
    }

    public function randomString($length)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-"),0,$length);
    }
}


$betty = new \Orange\Orange($host, $user, $pass, $db);
$auth = new \Orange\Authentication($betty->getBettyDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new \Orange\Profiler();
$gump = new GUMP('en');

if (!defined("SB_MEDIAWIKI")) {
    if ($isMaintenance) {
        $twig = new \Orange\Templating($betty);
        echo $twig->render("error.twig", [
            "error_title" => "Offline",
            "error_reason" => "This site is currently offline."
        ]);
        die();
    }

    $database = $betty->getBettyDatabase();

    if ( $ipban = $database->fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [MiscFunctions::get_ip_address()])) {
        $usersAssociatedWithIP = $database->fetchArray($database->query("SELECT name FROM users WHERE ip LIKE ?", [MiscFunctions::get_ip_address()]));
        $twig = new \Orange\Templating($betty);
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
        $storage = new \Orange\BunnyStorage($betty);
    } else {
        $storage = new \Orange\LocalStorage($betty);
    }
}