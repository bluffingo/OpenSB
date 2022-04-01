<?php
namespace squareBracket;
class sBTwigExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('browse_video_box', '\squareBracket\browseVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('small_video_box', '\squareBracket\smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('vertical_video_box', '\squareBracket\verticalVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', '\squareBracket\videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_thumbnail', '\squareBracket\videoThumbnail', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_length', '\squareBracket\videoLength', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('profile_image', '\squareBracket\profileImage', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('channel_background', '\squareBracket\channelBackground', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', '\squareBracket\userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('comment', '\squareBracket\comment', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('__', '\squareBracket\__', ['is_safe' => ['html']]), //localization
			new \Twig\TwigFunction('browse_channel_box', '\squareBracket\browseChannelBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('icon', '\squareBracket\icon', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('icon_alt', '\squareBracket\icon_alt', ['is_safe' => ['html']]), //intended for sbnext's upload_start -gr 10/8/2021
			new \Twig\TwigFunction('pagination', '\squareBracket\pagination', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('git_commit', '\squareBracket\gitCommit'),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('relative_time', '\squareBracket\relativeTime'),
			new \Twig\TwigFilter('category_id_to_name', '\squareBracket\categoryIDToName'),
			new \Twig\TwigFilter('json_decode', '\squareBracket\jsonDecode'),
			
			// Markdown function for non-inline text, sanitized.
			new \Twig\TwigFilter('markdown', function ($text) {
				$markdown = new \Parsedown();
				$markdown->setSafeMode(true);
				return $markdown->text($text);
			}, ['is_safe' => ['html']]),
			
			// Markdown function for inline text, sanitized.
			new \Twig\TwigFilter('markdown_inline', function ($text) {
				$markdown = new \Parsedown();
				$markdown->setSafeMode(true);
				return $markdown->line($text);
			}, ['is_safe' => ['html']]),

			// Markdown function for non-inline text. **NOT SANITIZED, DON'T LET IT EVER TOUCH USER INPUT**
			new \Twig\TwigFilter('markdown_unsafe', function ($text) {
				$markdown = new Parsedown();
				return $markdown->text($text);
			}, ['is_safe' => ['html']]),
		];
	}
}