<?php

namespace squareBracket;

use Parsedown;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class sBTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        global $profiler;

        return [
            new TwigFunction('browse_video_box', '\squareBracket\browseVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('small_video_box', '\squareBracket\smallVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('vertical_video_box', '\squareBracket\verticalVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('video_box', '\squareBracket\videoBox', ['is_safe' => ['html']]),
            new TwigFunction('video_thumbnail', '\squareBracket\videoThumbnail', ['is_safe' => ['html']]),
            new TwigFunction('video_length', '\squareBracket\videoLength', ['is_safe' => ['html']]),
            new TwigFunction('profile_image', '\squareBracket\profileImage', ['is_safe' => ['html']]),
            new TwigFunction('channel_background', '\squareBracket\channelBackground', ['is_safe' => ['html']]),
            new TwigFunction('userlink', '\squareBracket\Users::userlink', ['is_safe' => ['html']]), // why does it work
            new TwigFunction('comment', '\squareBracket\comment', ['is_safe' => ['html']]),
            new TwigFunction('__', '\squareBracket\__', ['is_safe' => ['html']]), //localization
            new TwigFunction('browse_channel_box', '\squareBracket\browseChannelBox', ['is_safe' => ['html']]),
            new TwigFunction('icon', '\squareBracket\icon', ['is_safe' => ['html']]),
            new TwigFunction('icon_alt', '\squareBracket\icon_alt', ['is_safe' => ['html']]), //intended for sbnext's upload_start -gr 10/8/2021
            new TwigFunction('pagination', '\squareBracket\pagination', ['is_safe' => ['html']]),
            new TwigFunction('git_commit', '\squareBracket\gitCommit'),
            new TwigFunction('operating_system', '\squareBracket\getOS'),
            new TwigFunction('profiler_stats', function () use ($profiler) {
                $profiler->getStats();
            })
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('relative_time', '\squareBracket\relativeTime'),
            new TwigFilter('category_id_to_name', '\squareBracket\Videos::categoryIDToName'),
            new TwigFilter('json_decode', '\squareBracket\jsonDecode'),

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
}