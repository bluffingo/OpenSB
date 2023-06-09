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
            return "Site user logged in as " . $auth->getUserData()["name"] . ".";
        } else {
            return "Site user is guest.";
        }
    }

    function whoAmI() {
        $whoami = exec('whoami');
        if ($whoami) {
            return "Running under system user " . $whoami;
        }
        return "Running under unknown system user";
    }

    function getStats() {
        printf("Rendered in %1.3fs with %dKB memory used. %s. %s",
            microtime(true) - $this->starttime,
            memory_get_usage(false) / 1024, $this->whoAmI(), $this->getAuthData());
    }
}