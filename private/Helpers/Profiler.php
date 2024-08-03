<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Helpers;

// people: how bad of a profiler can you make
// me:
class Profiler {
    protected static $startTime;

    public static function start() {
        self::$startTime = microtime(true);
    }

    public static function getInfo() {
        if (self::$startTime === null) {
            throw new \Exception("You haven't started the timer!");
        }

        printf("rendered in %1.3fs with %dKB memory used.", microtime(true) - self::$startTime, memory_get_usage(false) / 1024);
    }
}
