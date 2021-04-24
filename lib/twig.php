<?php

class SBExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('small_video_box', 'smallVideoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('video_box', 'videoBox', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('watch_box', 'watchBox', ['is_safe' => ['html']])
		];
	}
	public function getFilters() {
		return [

		];
	}
}
