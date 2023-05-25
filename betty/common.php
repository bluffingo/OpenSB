<?php

namespace Betty;

$betty_version = "milestone-1";

foreach (glob(dirname(__DIR__) . "/betty/class/*.php") as $file) {
    require_once($file);
}

// This lets us use the Database class within other Betty classes.
class Betty {
    private \Betty\Database $database;

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
}

$betty = new \Betty\Betty($host, $user, $pass, $db);