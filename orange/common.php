<?php

namespace Orange;

global $host, $user, $pass, $db, $buildNumber, $gitBranch;

use GUMP;

foreach (glob(dirname(__DIR__) . "/orange/interfaces/*.php") as $file) {
    require_once($file);
}

foreach (glob(dirname(__DIR__) . "/orange/classes/*.php") as $file) {
    require_once($file);
}

/**
 * @since 0.1.0
 */
class Orange {
    private \Orange\Database $database;
    public string $version = "Beta 3.1";
    public array $options;

    public function __construct($host, $user, $pass, $db) {
        session_start(["cookie_lifetime" => 0, "gc_maxlifetime" => 455800]);

        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        } else {
            $this->options = [];
        }
        try {
            $this->database = new \Orange\Database($host, $user, $pass, $db);
        } catch (BettyException $e) {
            $e->page();
        }
    }

    /**
     * Returns the database for other Betty classes to use.
     *
     * @since 0.1.0
     *
     * @return Database
     */
    public function getBettyDatabase(): \Orange\Database {
        return $this->database;
    }

    /**
     * Returns Betty's version number.
     *
     * @since 0.1.0
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
     * @since 0.1.0
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
     * @since 0.1.0
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
}

$betty = new \Orange\Orange($host, $user, $pass, $db);
$auth = new \Orange\Authentication($betty->getBettyDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new \Orange\Profiler();
$gump = new GUMP('en');

if ($isQoboTV) {
    $storage = new \Orange\BunnyStorage;
} else {
    $storage = new \Orange\LocalStorage;
}