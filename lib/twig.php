<?php

class SBExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('browse_video_box', 'browseVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('small_video_box', 'smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', 'videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_thumbnail', 'videoThumbnail', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_length', 'videoLength', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('profile_image', 'profileImage', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('channel_background', 'channelBackground', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('comment', 'comment', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('__', '__', ['is_safe' => ['html']]), //localization
			new \Twig\TwigFunction('browse_channel_box', 'browseChannelBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('icon', 'icon', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('icon_alt', 'icon_alt', ['is_safe' => ['html']]), //intended for sbnext's upload_start -gr 10/8/2021
			new \Twig\TwigFunction('pagination', 'pagination', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('git_commit', 'gitCommit'),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('relative_time', 'relativeTime'),
			new \Twig\TwigFilter('category_id_to_name', 'categoryIDToName'),
			new \Twig\TwigFilter('json_decode', 'jsonDecode'),
		];
	}
}