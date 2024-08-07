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
                return "placeholder";
            }, ['is_safe' => ['html']]),

            // thumbnail
            new TwigFunction('thumbnail', function ($upload) {
                return "placeholder";
            }, ['is_safe' => ['html']]),

            // user links
            new TwigFunction('user_link', function ($user_id) {
                return $user_id;
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
            new TwigFunction('show_ratings', function ($stars) {
                return "CURRENTLY UNFINISHED! (show_ratings)";
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
                return "CURRENTLY UNFINISHED! (relative_time)";
            }, ['is_safe' => ['html']]),
        ];
    }
}
