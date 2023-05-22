<?php

namespace openSB;

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
            new TwigFunction('browse_video_box', '\openSB\browseVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('small_video_box', '\openSB\smallVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('vertical_video_box', '\openSB\verticalVideoBox', ['is_safe' => ['html']]),
            new TwigFunction('video_box', '\openSB\videoBox', ['is_safe' => ['html']]),
            new TwigFunction('video_thumbnail', '\openSB\videoThumbnail', ['is_safe' => ['html']]),
            new TwigFunction('video_length', '\openSB\videoLength', ['is_safe' => ['html']]),
            new TwigFunction('profile_image', '\openSB\profileImage', ['is_safe' => ['html']]),
            new TwigFunction('channel_background', '\openSB\channelBackground', ['is_safe' => ['html']]),
            new TwigFunction('userlink', '\openSB\Users::userlink', ['is_safe' => ['html']]), // why does it work
            new TwigFunction('comment', '\openSB\comment', ['is_safe' => ['html']]),
            new TwigFunction('__', '\openSB\__', ['is_safe' => ['html']]), //localization
            new TwigFunction('browse_channel_box', '\openSB\browseChannelBox', ['is_safe' => ['html']]),
            new TwigFunction('icon', '\openSB\icon', ['is_safe' => ['html']]),
            new TwigFunction('pagination', '\openSB\pagination', ['is_safe' => ['html']]),
            new TwigFunction('git_commit', '\openSB\gitCommit'),
            new TwigFunction('operating_system', '\openSB\getOS'),
            new TwigFunction('upload_limit', '\openSB\convertBytes'),
            new TwigFunction('profiler_stats', function () use ($profiler) {
                $profiler->getStats();
            }),
            new TwigFunction('art_thumbnail', '\openSB\imageThumbnail', ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('relative_time', '\openSB\relativeTime'),
            new TwigFilter('category_id_to_name', '\openSB\Videos::categoryIDToName'),
            new TwigFilter('json_decode', '\openSB\jsonDecode'),

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