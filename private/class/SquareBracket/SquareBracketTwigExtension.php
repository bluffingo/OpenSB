<?php

namespace SquareBracket;

use Parsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SquareBracketTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        global $profiler, $orange;

        if ($orange->getLocalOptions()["skin"] == "biscuit") {
            $userlink_function_name = "UserLink";
        } else {
            $userlink_function_name = "UserLinkOld";
        }

        return [
            new TwigFunction('submission_view', [$this, 'submissionView']),
            new TwigFunction('thumbnail', [$this, 'thumbnail']),
            new TwigFunction('user_link', [$this, $userlink_function_name], ['is_safe' => ['html']]),
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
            new TwigFunction('header_user_account_links', [$this, 'headerUserAccountLinks']),
            new TwigFunction('get_css_file_date', [$this, 'getCSSFileDate']),
            new TwigFunction('submission_box', [$this, 'submissionBox'], ['is_safe' => ['html']]),
            new TwigFunction('comment', [$this, 'comment'], ['is_safe' => ['html']]),
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
     */
    function relativeTime($time) {
        if (!$time) return 'unknown';

        $relativeTime = new RelativeTime([
            'truncate' => 1,
        ]);

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

        // fyi: opensb still doesn't fully support music submissions.
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

        $id = UnorganizedFunctions::usernameToID($database, $username);
        $location = '/dynamic/pfp/' . $id . '.png';
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
        global $database, $storage;

        $id = UnorganizedFunctions::usernameToID($database, $username);
        $location = '/dynamic/banners/' . $id . '.png';

        if ($storage->fileExists('..' . $location)) {
            $data = $location;
        } else {
            $data = "/assets/biscuit_banner.svg";
        }
        return $data;
    }

    // new userlink used on biscuit
    public function UserLink($user): string
    {
        // Extract and sanitize user information
        $username = htmlspecialchars($user["info"]["username"]);
        $displayName = htmlspecialchars($user["info"]["displayname"]);
        $customColor = htmlspecialchars($user["info"]["customcolor"]);

        // Define common values
        $href = "/user/" . $username;
        $class = "userlink userlink-" . $username;
        $style = "color:" . $customColor;

        // Determine the display text
        if ($username === $displayName) {
            $displayText = sprintf('<span style="%s">@%s</span>', $style, $username);
        } else {
            $displayText = sprintf(
                '%s <a class="userlink-handle" style="text-decoration: none;" href="%s">@%s</a>',
                $displayName,
                $href,
                $username
            );
        }

        // Return the formatted link
        return sprintf('<a class="%s" style="%s" href="%s">%s</a>', $class, $style, $href, $displayText);
    }

    // old userlink used on bootstrap and finalium
    public function UserLinkOld($user): string
    {
        return <<<HTML
<a class="userlink userlink-{$user["info"]["username"]}" style="color:{$user["info"]["customcolor"]};" href="/user/{$user["info"]["username"]}">{$user["info"]["username"]}</a>
HTML;
    }

    public function removeNotification()
    {
        unset($_SESSION["notif_message"]);
        unset($_SESSION["notif_color"]);
    }

    public function showRatings($ratings): void
    {
        $full = "biscuit-icon star-full";
        $half = "biscuit-icon star-half";
        $empty = "biscuit-icon star-empty";

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
            "members" => [
                "name" => "Members",
                "url" => "/users",
            ],
        ];
    }

    public function headerUserLinks()
    {
        global $auth;

        if ($auth->isUserLoggedIn()) {
            $username = $auth->getUserData()["name"];

            $array = [
                "profile" => [
                    "name" => "My profile",
                    "url" => "/user/" . $username,
                ],
                "my_uploads" => [
                    "name" => "My uploads",
                    "url" => "/my_uploads",
                ],
                "settings" => [
                    "name" => "Account settings",
                    "url" => "/settings",
                ],
                "upload" => [
                    "name" => "Upload",
                    "url" => "/upload",
                ],
                "new_journal" => [
                    "name" => "Write journal",
                    "url" => "/write",
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

    public function headerUserAccountLinks()
    {
        global $orange, $database;
        $accountsArray = $orange->getAccountsArray();

        $array = [];

        foreach ($accountsArray as $account) {
            $data = $database->result("SELECT name FROM users WHERE id = ?", [$account["userid"]]);

            $array[] = [
                "id" => $account["userid"],
                "username" => $data,
            ];
        }

        return $array;
    }

    public function getCSSFileDate()
    {
        return filemtime(SB_PUBLIC_PATH . "/assets/css/default.css");
    }

    public function submissionBox($submission)
    {
        global $twig;
        return $twig->render('components/smallvideobox.twig', ['data' => $submission]);
    }

    public function comment($comment)
    {
        global $twig;
        return $twig->render('components/comment.twig', ['data' => $comment]);
    }
}