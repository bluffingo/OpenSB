<?php

namespace Orange;

use Parsedown;
use RelativeTime\RelativeTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OrangeTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        global $profiler;

        return [
            new TwigFunction('submission_view', [$this, 'SubmissionView']),
            new TwigFunction('thumbnail', [$this, 'Thumbnail']),
            new TwigFunction('user_link', [$this, 'UserLink'], ['is_safe' => ['html']]),
            new TwigFunction('profile_picture', [$this, 'ProfilePicture']),
            new TwigFunction('profile_banner', [$this, 'ProfileBanner']),
            new TwigFunction('profiler_stats', function () use ($profiler) {
                $profiler->getStats();
            }),
            new TwigFunction('remove_notification', [$this, 'RemoveNotification']),
            new TwigFunction('show_ratings', [$this, 'ShowRatings']),
            new TwigFunction('notification_icon', [$this, 'NotificationIcon']),
            new TwigFunction('pagination', [$this, 'Pagination'], ['is_safe' => ['html']]),
            new TwigFunction('random_slogan', [$this, 'RandomSlogan'], ['is_safe' => ['html']]),
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
                $parsed_text = preg_replace('/(?<!=|\b|&)@([a-z0-9_]+)/i', '<a href="/user?name=$1">@$1</a>', $parsed_text);


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
                $parsed_text = preg_replace('/(?<!=|\b|&)@([a-z0-9_]+)/i', '<a href="/user?name=$1">@$1</a>', $parsed_text);


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

    public function SubmissionView($submission_data)
    {
        global $twig;
        if (!$submission_data) {
            throw new OrangeException('SubmissionView is null', 500);
        };
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

    public function Thumbnail($id, $type)
    {
        global $isQoboTV, $storage, $bunnySettings;

        $custom_location = '/dynamic/custom_thumbnails/' . $id . '.jpg';

        $data = null;

        if ($storage->fileExists('..' . $custom_location)) {
            if ($isQoboTV) {
                $data = "https://" . $bunnySettings["pullZone"] . $custom_location;
            } else {
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

    public function ProfilePicture($username)
    {
        global $isQoboTV, $bunnySettings, $storage;
        $location = '/dynamic/pfp/' . $username . '.png';

        if($storage->fileExists('..' . $location)) {
            if ($isQoboTV) {
                $data = "https://" . $bunnySettings["pullZone"] . $location;
            } else {
                $data = $location;
            }
        } else {
            $data = "/assets/placeholder/profiledef.png";
        }
        return $data;
    }

    public function ProfileBanner($username)
    {
        global $isQoboTV, $bunnySettings, $storage;
        $location = '/dynamic/banners/' . $username . '.png';

        if($storage->fileExists('..' . $location)) {
            if ($isQoboTV) {
                $data = "https://" . $bunnySettings["pullZone"] . $location;
            } else {
                $data = $location;
            }
        } else {
            $data = false;
        }
        return $data;
    }

    public function UserLink($user): string
    {
        global $auth;

        $id = $user["id"];

        if ($auth->isUserLoggedIn() && MiscFunctions::IsFollowingUser($id)) {
            $class = "userlink following";
        } else {
            $class = "userlink";
        }

        return <<<HTML
<a class="{$class}" href="/user/{$user["info"]["username"]}">{$user["info"]["username"]}</a>
HTML;
    }

    public function RemoveNotification()
    {
        unset($_SESSION["notif_message"]);
        unset($_SESSION["notif_color"]);
    }

    public function ShowRatings($ratings): void
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

        while($number != 5) {
            $number++;
            echo "<i class='$empty'></i>";
        }

    }

    public function NotificationIcon($type)
    {
        $icon = "bi bi-info-circle";

        if ($type == "danger") { $icon = "bi bi-x-circle-fill"; }
        if ($type == "success") { $icon = "bi bi-check-circle-fill"; }

        return $icon;
    }

    public function Pagination($levels, $lpp, $url, $current)
    {
        global $twig;
        return $twig->render('components/pagination.twig', ['levels' => $levels, 'lpp' => $lpp, 'url' => $url, 'current' => $current]);
    }

    public function RandomSlogan()
    {
        $slogans = [
            "Your content, your narration, your niche on the web.",
            "Technically BitView's sister site.",
            "I can't believe it's not squareBracket!",
            "I can't believe it's not CleberTube!",
            "Coconuts have water in them!",
            "Made in Quebec yet not available in French.",
            "Not to be confused with Qubo.",
            "Remember 2003page?",
        ];

        return array_rand(array_flip($slogans));
    }
}