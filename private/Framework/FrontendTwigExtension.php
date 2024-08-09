<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

use OpenSB\App;

use Parsedown;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FrontendTwigExtension extends \Twig\Extension\AbstractExtension
{
    private $feature_flags;

    public function __construct() {
        $this->feature_flags = App::container()->get(SiteConfig::class)->getFeatureFlags();
    }

    public function getFunctions()
    {
        return [
            // profile pictures
            new TwigFunction('profile_picture', function ($username) {
                return "/assets/profiledef.png";
            }, ['is_safe' => ['html']]),

            // thumbnail
            new TwigFunction('thumbnail', function ($upload) {
                return "/assets/placeholder/placeholder.png";
            }, ['is_safe' => ['html']]),

            // user links
            new TwigFunction('user_link', function ($user_id) {
                // temporary
                return $user_id["info"]["username"];
            }, ['is_safe' => ['html']]),

            // localization
            new TwigFunction('localize', function ($string) {
                return $string;
            }, ['is_safe' => ['html']]),

            // cache bypass
            new TwigFunction('get_css_file_date', function () {
                return filemtime($_SERVER["DOCUMENT_ROOT"] . '/assets/css/default.css');
            }, ['is_safe' => ['html']]),

            // components
            new TwigFunction('component', function ($component) {
                return '/components/' . 'default' . '/' . $component . '.twig';
            }, ['is_safe' => ['html']]),

            // footer-related stuff?
            new TwigFunction('version_banner', function () {
                return "placeholder";
            }, ['is_safe' => ['html']]),

            new TwigFunction('profiler_stats', function () {
                return "placeholder";
            }, ['is_safe' => ['html']]),

            // notification stuff
            new TwigFunction('notification_icon', function () {
                return "placeholder";
            }, ['is_safe' => ['html']]),

            new TwigFunction('remove_notification', function () {
                unset($_SESSION["notif_message"]);
                unset($_SESSION["notif_color"]);
            }, ['is_safe' => ['html']]),

            // header links
            new TwigFunction('header_main_links', function () {
                $array = [
                    "browse" => [
                        "name" => "Browse",
                        "url" => "/browse",
                    ],
                    "members" => [
                        "name" => "Members",
                        "url" => "/users",
                    ],
                ];

                if ($this->feature_flags["SBChat_Enable"]) {
                    $array["chat"] = [
                        "name" => "Chat",
                        "url" => "/chat",
                    ];
                }

                return $array;
            }, ['is_safe' => ['html']]),

            new TwigFunction('header_user_links', function () {
                return [
                    "browse" => [
                        "name" => "Login",
                        "url" => "/login",
                    ],
                    "members" => [
                        "name" => "Register",
                        "url" => "/register",
                    ],
                ];
            }, ['is_safe' => ['html']]),

            new TwigFunction('header_user_account_links', function () {
                return [
                    "browse" => [
                        "name" => "Browse",
                        "url" => "/browse",
                    ],
                    "members" => [
                        "name" => "Members",
                        "url" => "/users",
                    ],
                ];
            }, ['is_safe' => ['html']]),

            // upload view
            new TwigFunction('submission_view', function () {
                return "placeholder";
            }, ['is_safe' => ['html']]),

            // star ratings
            new TwigFunction('show_ratings', function ($ratings) {
                $icons = [
                    'full' => "biscuit-icon star-full",
                    'half' => "biscuit-icon star-half",
                    'empty' => "biscuit-icon star-empty"
                ];

                $full_stars = substr($ratings["average"], 0, 1);
                $half_stars = substr($ratings["average"], 2, 1);

                for ($i = 0; $i < $full_stars; $i++) {
                    echo "<i class='{$icons['full']}'></i>";
                }

                if ($half_stars) {
                    echo "<i class='" . ($full_stars == 4 ? $icons['full'] : $icons['half']) . "'></i>";
                    $full_stars++;
                }

                for ($i = $full_stars; $i < 5; $i++) {
                    echo "<i class='{$icons['empty']}'></i>";
                }
            }, ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('markdown_unsafe', function ($text) {
                $parsedown = new Parsedown();
                return $parsedown->text($text);
            }, ['is_safe' => ['html']]),

            new TwigFilter('markdown_user_journal', function ($text) {
                return "CURRENTLY UNFINISHED! (markdown_user_journal)";
            }, ['is_safe' => ['html']]),

            new TwigFilter('relative_time', function ($time) {
                if ($time == 0) {
                    return "unknown";
                }

                $time_difference = time() - $time;
                $units = [
                    31536000 => 'year',
                    2592000  => 'month',
                    604800   => 'week',
                    86400    => 'day',
                    3600     => 'hour',
                    60       => 'minute',
                    1        => 'second'
                ];

                foreach ($units as $unit => $text) {
                    if ($time_difference < $unit) continue;
                    $numberOfUnits = floor($time_difference / $unit);
                    return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '') . ' ago';
                }

                return 'just now';
            }, ['is_safe' => ['html']]),
        ];
    }
}
