<?php

namespace Betty;

global $host, $user, $pass, $db, $buildNumber, $gitBranch;
foreach (glob(dirname(__DIR__) . "/betty/interfaces/*.php") as $file) {
    require_once($file);
}

foreach (glob(dirname(__DIR__) . "/betty/class/*.php") as $file) {
    require_once($file);
}

/**
 * This lets us use the Database class within other Betty classes.
 *
 * @since 0.1.0
 *
 */
class Betty {
    private \Betty\Database $database;
    public string $version = "0.1.0";
    public array $options;

    public function __construct($host, $user, $pass, $db) {
        if (isset($_COOKIE["SBOPTIONS"])) {
            $this->options = json_decode(base64_decode($_COOKIE["SBOPTIONS"]), true);
        } else {
            $this->options = [];
        }
        try {
            $this->database = new \Betty\Database($host, $user, $pass, $db);
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
    public function getBettyDatabase(): \Betty\Database {
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
}

$betty = new \Betty\Betty($host, $user, $pass, $db);
$opensb_version = new \Betty\OpenSbVersion($buildNumber, $gitBranch);
$auth = new \Betty\Authentication($betty->getBettyDatabase(), $_COOKIE['SBTOKEN'] ?? null);
$profiler = new \Betty\Profiler();

if ($isQoboTV) {
    $storage = new \Betty\BunnyStorage;
} else {
    $storage = new \Betty\LocalStorage;
}