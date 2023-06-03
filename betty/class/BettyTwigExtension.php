<?php

namespace Betty;

use Parsedown;
use RelativeTime\RelativeTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class BettyTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        global $profiler;

        return [
            new TwigFunction('submission_view', [$this, 'SubmissionView']),
            new TwigFunction('thumbnail', [$this, 'Thumbnail']),
            new TwigFunction('user_link', [$this, 'UserLink'], ['is_safe' => ['html']]),
            new TwigFunction('profile_picture', [$this, 'ProfilePicture']),
            new TwigFunction('profiler_stats', function () use ($profiler) {
                $profiler->getStats();
            }),
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
            throw new BettyException('Submission is null', 500);
        };
        if ($submission_data["type"] == 0) {
            echo $twig->render("player.twig", ['submission' => $submission_data]);
        }

        if ($submission_data["type"] == 2) {
            echo $twig->render("image.twig", ['submission' => $submission_data]);
        }
    }

    public function Thumbnail($id, $type)
    {
        global $isQoboTV, $storage;
        if ($type == 0) {
            if ($isQoboTV) {
                $data = $storage->getVideoThumbnail($id);
            } else {
                $data = "/assets/placeholder/placeholder.png";
            }
        }
        if ($type == 2) {
            if ($isQoboTV) {
                $data = $storage->getImageThumbnail($id);
            } else {
                $data = "/assets/placeholder/placeholder.png";
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
                $data = $username;
            }
        } else {
            $data = "/assets/placeholder/profiledef.png";
        }
        return $data;
    }

    public function UserLink($user)
    {
        return <<<HTML
<a style="color: {$user["info"]["color"]}" href="user.php?name={$user["info"]["username"]}">{$user["info"]["username"]}</a>
HTML;
    }
}