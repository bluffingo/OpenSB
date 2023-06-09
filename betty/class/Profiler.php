<?php

namespace Betty;

/**
 * Revamped profiler.
 *
 * @since 0.1.0
 */
class Profiler
{
    private $starttime;

    function __construct() {
        $this->starttime = microtime(true);
    }

    function getAuthData() {
        global $auth;
        if ($auth->isUserLoggedIn()) {
            return "User logged in as " . $auth->getUserData()["name"] . ".";
        } else {
            return "User is guest.";
        }
    }

    function getStats() {
        printf("Rendered in %1.3fs with %dKB memory used. %s",
            microtime(true) - $this->starttime,
            memory_get_usage(false) / 1024, $this->getAuthData());
    }
}