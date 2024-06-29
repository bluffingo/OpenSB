<?php

namespace SquareBracket;

/*
    Copyright (C) 2013 by Michael Pratt <pratt@hablarmierda.net>

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
*/

// Cut down version of RelativeTime: https://github.com/mpratt/RelativeTime
//https://github.com/principia-game/principia-web/blob/master/lib/relativetime.php
use DateInterval;
use DateTime;

/**
 * The Main Class of the library
 */
class RelativeTime {

    /** @var array Array With configuration options **/
    protected $config = [];

    protected $strings = [
        'seconds' => [
            'plural' => '%d seconds',
            'singular' => '%d second',
        ],
        'minutes' => [
            'plural' => '%d minutes',
            'singular' => '%d minute',
        ],
        'hours' => [
            'plural' => '%d hours',
            'singular' => '%d hour',
        ],
        'days' => [
            'plural' => '%d days',
            'singular' => '%d day',
        ],
        'weeks' => [
            'plural' => '%d weeks',
            'singular' => '%d week',
        ],
        'months' => [
            'plural' => '%d months',
            'singular' => '%d month',
        ],
        'years' => [
            'plural' => '%d years',
            'singular' => '%d year',
        ],
    ];

    /**
     * Construct
     *
     * @param array $config Associative array with configuration directives
     */
    public function __construct(array $config = []) {
        $this->config = array_merge([
            'separator' => ', ',
            'suffix' => true,
            'truncate' => 0,
        ], $config);
    }

    /**
     * Converts 2 dates to its relative time.
     *
     * @param string $fromTime
     * @param string $toTime When null is given, uses the current date.
     * @return string
     */
    public function convert($fromTime, $toTime = null)
    {
        $interval = $this->getInterval($fromTime, $toTime);
        $units = $this->calculateUnits($interval);

        return $this->translate($units, $interval->invert);
    }

    /**
     * Tells the time passed between the current date and the given date
     *
     * @param string $date
     * @return string
     */
    public function timeAgo($date) {
        $interval = $this->getInterval(time(), $date);
        if ($interval->invert)
            return $this->convert(time(), $date);

        return $this->translate();
    }

    /**
     * Calculates the interval between the dates and returns
     * an array with the valid time.
     *
     * @param string $fromTime
     * @param string $toTime When null is given, uses the current date.
     * @return DateInterval
     */
    protected function getInterval($fromTime, $toTime = null) {
        $fromTime = new DateTime($this->normalizeDate($fromTime));
        $toTime = new DateTime($this->normalizeDate($toTime));

        return $fromTime->diff($toTime);
    }

    /**
     * Normalizes a date for the DateTime class
     *
     * @param string $date
     * @return string
     */
    protected function normalizeDate($date) {
        $date = str_replace(['/', '|'], '-', $date);

        if (empty($date))
            return date('Y-m-d H:i:s');
        else if (ctype_digit($date))
            return date('Y-m-d H:i:s', $date);

        return $date;
    }

    /**
     * Given a DateInterval, creates an array with the time
     * units and truncates it when necesary.
     *
     * @param DateInterval $interval
     * @return array
     */
    protected function calculateUnits(DateInterval $interval) {
        $units = array_filter([
            'years'   => (int)$interval->y,
            'months'  => (int)$interval->m,
            'weeks'   => 1, // We have to assign this here so we can preserve the order of the units.
            'days'    => (int)$interval->d,
            'hours'   => (int)$interval->h,
            'minutes' => (int)$interval->i,
            'seconds' => (int)$interval->s,
        ]);

        if (isset($units['days']) && $units['days'] > 6) {
            $units['weeks'] = floor($units['days'] / 7);
            $units['days'] = ($units['days'] - floor($units['weeks'] * 7));
            if ($units['days'] <= 0) {
                unset($units['days']);
            }
        } else {
            unset($units['weeks']);
        }

        if (empty($units))
            return [];
        elseif ((int)$this->config['truncate'] > 0)
            return array_slice($units, 0, (int)$this->config['truncate']);

        return $units;
    }

    /**
     * Actually translates the units into words
     *
     * @param array $units
     * @param int $direction
     * @return string
     */
    protected function translate(array $units = [], $direction = 0) {
        if (empty($units))
            return 'just now';

        $translation = [];
        foreach ($units as $unit => $v) {
            if ($v == 1)
                $translation[] = sprintf($this->strings[$unit]['singular'], $v);
            else
                $translation[] = sprintf($this->strings[$unit]['plural'], $v);
        }

        $string = implode($this->config['separator'], $translation);
        if (!$this->config['suffix'])
            return $string;

        if ($direction > 0)
            return sprintf("%s ago", $string);
        else
            return sprintf("%s left", $string);
    }
}