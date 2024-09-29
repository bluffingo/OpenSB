<?php

namespace SquareBracket;

/**
 * Revamped profiler.
 */
class Profiler
{
    private $starttime;

    function __construct() {
        $this->starttime = microtime(true);
    }

    function getAuthData(): string
    {
        global $auth;
        if ($auth->isUserLoggedIn()) {
            return "Site user is logged in as " . htmlspecialchars($auth->getUserData()["name"]) . ".";
        } else {
            return "Site user is logged out.";
        }
    }

    function whoAmI(): string
    {
        $whoami = exec('whoami');
        if ($whoami) {
            return "Running under system user " . $whoami;
        }
        return "Running under unknown system user";
    }

    function getStats(): void
    {
        printf("Rendered in %1.8fs with %dKB memory used. %s. %s",
            microtime(true) - $this->starttime,
            memory_get_usage(false) / 1024, $this->whoAmI(), $this->getAuthData());
    }
}