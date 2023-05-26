<?php

namespace openSB;
class Profiler
{
    private $starttime;

    public function __construct()
    {
        $this->starttime = microtime(true);
    }

    public function __destruct()
    {
        $this->getStats();
    }

    public function getStats()
    {
        global $userdata, $isMaintenance, $frontendName, $bettyTemplate;

        if (isCli()) return;
        $headers = headers_list();

        foreach ($headers as $index => $value) {
            list($key, $value) = explode(': ', $value);

            unset($headers[$index]);

            $headers[$key] = $value;
        }
        //check if we are outputing html. if not, simply abort.
        if (isset($headers["Content-Type"])) {
            if ($headers["Content-Type"] != "text/html") return;
        }

        $username = ($userdata['name'] ?? 'not logged in');
        $displayname = ($userdata['title'] ?? '');
        $language = ($userdata['language'] ?? 'en_US');
        $memoryUsage = memory_get_usage(false) / 1024;
        $renderTime = microtime(true) - $this->starttime;
        $res = getrusage();

        if ($isMaintenance) {
            $debugData = sprintf(
                '[debug]: Offline instance | user time used: %s | system time used: %s | current locale: %s | page rendered in %1.3f secs with %dKB used',
                $res["ru_utime.tv_sec"], $res["ru_stime.tv_sec"], $language, $renderTime, $memoryUsage);
        } else {
            $debugData = sprintf(
                '[debug]: %s (display name: @%s) | user time used: %s | system time used: %s | current locale: %s | page rendered in %1.3f secs with %dKB used',
                $username, htmlspecialchars($displayname), $res["ru_utime.tv_sec"], $res["ru_stime.tv_sec"], $language, $renderTime, $memoryUsage);
        }

        if ($frontendName == "sbnext" & $bettyTemplate == "finalium") {
            print('<div class="footer-sticky">' . $debugData . '</div>');
        } else {
            print('<center>' . $debugData . '</center>');
        }
    }
}