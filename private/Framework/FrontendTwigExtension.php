<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use OpenSB\Framework\DateUtilities;
use Parsedown;

class FrontendTwigExtension extends \Twig\Extension\AbstractExtension
{
    private $date;

    public function __construct() {
        $this->date = new DateUtilities();
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('wayback_url', function ($url) {
                $date = $this->date->currentQoboTimeToWaybackMachineURLDate();
                $time = date("His", time());

                return "http://web.archive.org/web/$date$time/$url";
            }, ['is_safe' => ['html']])
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('parse_md_read_only', function ($text) {
                $parsedown = new Parsedown();
                return $parsedown->text($text);
            }, ['is_safe' => ['html']]),

            new \Twig\TwigFilter('parse_md_user_written', function ($text) {
                return "CURRENTLY UNFINISHED! (parse_md_user_written)";
            }, ['is_safe' => ['html']]),

            new \Twig\TwigFilter('qobo_date', function ($date, $parameters) {
                $qobo_date = $this->date->actualTimeToQoboTime($date);
                return date($parameters, $qobo_date);
            }, ['is_safe' => ['html']]),
        ];
    }
}
