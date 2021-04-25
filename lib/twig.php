<?php

class SBExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('small_video_box', 'smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', 'videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('watch_box', 'watchBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_thumbnail', 'videoThumbnail', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('relative_time', 'relativeTime'),
		];
	}
}
