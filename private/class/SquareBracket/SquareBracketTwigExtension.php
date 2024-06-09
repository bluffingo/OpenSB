<?php

namespace SquareBracket;

use Parsedown;
use RelativeTime\RelativeTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SquareBracketTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        global $profiler, $orange;

        return [
            new TwigFunction('submission_view', [$this, 'submissionView']),
            new TwigFunction('thumbnail', [$this, 'thumbnail']),
            new TwigFunction('user_link', [$this, 'UserLink'], ['is_safe' => ['html']]),
            new TwigFunction('profile_picture', [$this, 'profilePicture']),
            new TwigFunction('profile_banner', [$this, 'profileBanner']),
            new TwigFunction('profiler_stats', function () use ($profiler) {
                $profiler->getStats();
            }),
            new TwigFunction('version_banner', function () use ($orange) {
                echo (new \SquareBracket\VersionNumber)->printVersionForOutput();
            }),
            new TwigFunction('remove_notification', [$this, 'removeNotification']),
            new TwigFunction('show_ratings', [$this, 'showRatings']),
            new TwigFunction('notification_icon', [$this, 'notificationIcon']),
            new TwigFunction('pagination', [$this, 'pagination'], ['is_safe' => ['html']]),
            new TwigFunction('header_main_links', [$this, 'headerMainLinks']),
            new TwigFunction('header_user_links', [$this, 'headerUserLinks']),
            new TwigFunction('get_css_file_date', [$this, 'getCSSFileDate']),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('relative_time', [$this, 'relativeTime']),

            // Markdown function for non-inline text, sanitized.
            new TwigFilter('markdown', function ($text) {
                $markdown = new Parsedown();
                $markdown->setSafeMode(true);
                return $markdown->text($text);
            }, ['is_safe' => ['html']]),

            // Markdown function for inline text, sanitized.
            new TwigFilter('markdown_inline', function ($text) {
                $markdown = new Parsedown();
                $markdown->setSafeMode(true);
                return $markdown->line($text);
            }, ['is_safe' => ['html']]),

            // Markdown function for any posts.
            new TwigFilter('markdown_user_written', function ($text) {
                $markdown = new ParsedownExtension();
                $markdown->setSafeMode(true);
                $markdown->setUrlsLinked(true);

                $parsed_text = $markdown->text($text);

                // Hashtags
                $parsed_text = preg_replace('/(?<!=|\b|&)#([a-z0-9_]+)/i', '<a href="/search?tags=$1">#$1</a>', $parsed_text);

                // Mentions
                $parsed_text = preg_replace('/(?<!=|\b|&)@([a-z0-9_]+(?:@[a-z0-9.-]+)?)/i', '<a href="/user/$1">@$1</a>', $parsed_text);


                return $parsed_text;

            }, ['is_safe' => ['html']]),

            // Markdown function for any journals.
            new TwigFilter('markdown_user_journal', function ($text) {
                $markdown = new Parsedown();
                $markdown->setSafeMode(true);
                $markdown->setUrlsLinked(true);

                $parsed_text = $markdown->text($text);

                // Hashtags
                $parsed_text = preg_replace('/(?<!=|\b|&)#([a-z0-9_]+)/i', '<a href="/search?tags=$1">#$1</a>', $parsed_text);

                // Mentions
                $parsed_text = preg_replace('/(?<!=|\b|&)@([a-z0-9_]+(?:@[a-z0-9.-]+)?)/i', '<a href="/user/$1">@$1</a>', $parsed_text);


                return $parsed_text;

            }, ['is_safe' => ['html']]),

            // Markdown function for non-inline text. **NOT SANITIZED, DON'T LET IT EVER TOUCH USER INPUT**
            new TwigFilter('markdown_unsafe', function ($text) {
                $markdown = new Parsedown();
                return $markdown->text($text);
            }, ['is_safe' => ['html']]),
        ];
    }

    /**
     * Relative time function.
     *
     * @since openSB Pre-Alpha 1?
     */
    function relativeTime($time): string
    {
        $config = [
            'language' => '\RelativeTime\Languages\English',
            'separator' => ', ',
            'suffix' => true,
            'truncate' => 1,
        ];

        $relativeTime = new RelativeTime($config);

        return $relativeTime->timeAgo($time);
    }

    public function submissionView($submission_data)
    {
        global $twig;
        if (!$submission_data) {
            throw new CoreException('SubmissionView is null', 500);
        }
        if ($submission_data["type"] == 0) {
            echo $twig->render("player.twig", ['submission' => $submission_data]);
        }

        if ($submission_data["type"] == 2) {
            echo $twig->render("image.twig", ['submission' => $submission_data]);
        }

        if ($submission_data["type"] == 3) {
            echo $twig->render("music.twig", ['submission' => $submission_data]);
        }
    }

    public function thumbnail($id, $type, $custom)
    {
        global $storage;

        $custom_location = '/dynamic/custom_thumbnails/' . $id . '.jpg';

        $data = null;

        if ($custom) {
            if ($storage->fileExists('..' . $custom_location)) {
                $data = $custom_location;
            }
        } else {
            if ($type == 0) {
                $data = $storage->getVideoThumbnail($id);
            }
            if ($type == 2) {
                $data = $storage->getImageThumbnail($id);
            }
        }

        return $data;
    }

    public function profilePicture($username)
    {
        global $database, $storage;
        $location = '/dynamic/pfp/' . $username . '.png';

        $id = UnorganizedFunctions::usernameToID($database, $username);
        // don't bother with userdata since that might slow shit down
        $is_banned = $database->fetch("SELECT * FROM bans WHERE userid = ?", [$id]);

        if ($is_banned) {
            $data = "/assets/profiledel.png";
        } else {
            if ($storage->fileExists('..' . $location)) {
                $data = $location;
            } else {
                $data = "/assets/profiledef.png";
            }
        }

        return $data;
    }

    public function profileBanner($username)
    {
        global $storage;
        $location = '/dynamic/banners/' . $username . '.png';

        if ($storage->fileExists('..' . $location)) {
            $data = $location;
        } else {
            $data = "/assets/sbnext_channel_header_template.png";
        }
        return $data;
    }

    public function UserLink($user): string
    {
        global $auth;

        $id = $user["id"];

        if ($auth->isUserLoggedIn() && UnorganizedFunctions::IsFollowingUser($id)) {
            $class = "userlink following";
        } else {
            $class = "userlink";
        }

        return <<<HTML
<a class="{$class}" href="/user/{$user["info"]["username"]}">{$user["info"]["username"]}</a>
HTML;
    }

    public function removeNotification()
    {
        unset($_SESSION["notif_message"]);
        unset($_SESSION["notif_color"]);
    }

    public function showRatings($ratings): void
    {
        $full = "bi bi-star-fill rating-spacing";
        $half = "bi bi-star-half rating-spacing";
        $empty = "bi bi-star rating-spacing";

        $full_stars = substr($ratings["average"], 0, 1);
        $half_stars = substr($ratings["average"], 2, 1);

        $number = 0;

        for ($x = 0; $x < $full_stars; $x++) {
            $number++;
            echo "<i class='$full'></i>";
        }

        if ($half_stars) {
            $number++;
            if ($full_stars != 4) {
                echo "<i class='$half'></i>";
            } else {
                echo "<i class='$full'></i>";
            }
        }

        while ($number != 5) {
            $number++;
            echo "<i class='$empty'></i>";
        }

    }

    public function notificationIcon($type)
    {
        $icon = "bi bi-info-circle-fill";

        if ($type == "danger") {
            $icon = "bi bi-x-circle-fill";
        }

        if ($type == "success") {
            $icon = "bi bi-check-circle-fill";
        }

        if ($type == "warning") {
            $icon = "bi bi-exclamation-triangle-fill";
        }

        return $icon;
    }

    public function pagination($levels, $lpp, $url, $current)
    {
        global $twig;
        return $twig->render('components/pagination.twig', ['levels' => $levels, 'lpp' => $lpp, 'url' => $url, 'current' => $current]);
    }

    public function headerMainLinks()
    {
        return [
            "browse" => [
                "name" => "Browse",
                "url" => "/browse",
            ],
            /*
            "members" => [
                "name" => "Members",
                "url" => "/users",
            ],
            */
        ];
    }

    public function headerUserLinks()
    {
        global $auth;

        if ($auth->isUserLoggedIn()) {
            $username = $auth->getUserData()["name"];

            $array = [
                "profile" => [
                    "name" => $username,
                    "url" => "/user/" . $username,
                ],
                "my_submissions" => [
                    "name" => "My submissions",
                    "url" => "/my_submissions",
                ],
                "settings" => [
                    "name" => "Settings",
                    "url" => "/settings",
                ],
                "new_journal" => [
                    "name" => "Write",
                    "url" => "/write",
                ],
                "new_submission" => [
                    "name" => "Upload",
                    "url" => "/upload",
                ],
                "logout" => [
                    "name" => "Log out",
                    "url" => "/logout",
                ],
            ];

            if ($auth->isUserAdmin()) {
                $array["admin"] = [
                    "name" => "Admin",
                    "url" => "/admin",
                ];
            }
        } else {
            $array = [
                "login" => [
                    "name" => "Login",
                    "url" => "/login",
                ],
                "register" => [
                    "name" => "Register",
                    "url" => "/register",
                ],
            ];
        }

        return $array;
    }

    public function getCSSFileDate()
    {
        return filemtime(SB_PUBLIC_PATH . "/assets/css/default.css");
    }
}