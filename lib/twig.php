<?php

class SBExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('browse_video_box', 'browseVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('small_video_box', 'smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', 'videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_thumbnail', 'videoThumbnail', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('profile_image', 'profileImage', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('channel_background', 'channelBackground', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('comment', 'comment', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('__', '__', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('browse_channel_box', 'browseChannelBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('icon', 'icon', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('pagination', 'pagination', ['is_safe' => ['html']]),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('relative_time', 'relativeTime'),
			new \Twig\TwigFilter('json_decode', 'jsonDecode'),
			new \Twig\TwigFilter('category_id_to_name', 'categoryIDToName'),
		];
	}
}
