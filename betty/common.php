<?php

namespace Betty;

global $host, $user, $pass, $db, $buildNumber, $gitBranch;
foreach (glob(dirname(__DIR__) . "/betty/class/*.php") as $file) {
    require_once($file);
}

/**
 * This lets us use the Database class within other Betty classes.
 */
class Betty {
    private \Betty\Database $database;
    public $version = "0.1.0";

    public function __construct($host, $user, $pass, $db) {
        try {
            $this->database = new \Betty\Database($host, $user, $pass, $db);
        } catch (BettyException $e) {
            $e->page();
        }
    }

    public function getBettyDatabase(): \Betty\Database {
        return $this->database;
    }

    public function getBettyVersion() {
        return $this->version;
    }
}

$betty = new \Betty\Betty($host, $user, $pass, $db);
$opensb_version = new \Betty\OpenSbVersion($buildNumber, $gitBranch);