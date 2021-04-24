<?php

class SBExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('video_box', 'videoBox', ['is_safe' => ['html']]),
		];
	}
	public function getFilters() {
		return [

		];
	}
}
