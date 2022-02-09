<?php

class SBStudioExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('small_video_box', '_smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', '_videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('watch_box', '_watchBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_thumbnail', '_videoThumbnail', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('profile_image', '_profileImage', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', '_userlink', ['is_safe' => ['html']]),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('relative_time', 'relativeTime'),
		];
	}
}
