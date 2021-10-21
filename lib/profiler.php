<?php

class Profiler {
	private $starttime;

	function __construct() {
		$this->starttime = microtime(true);
	}

	function __destruct() {
		$this->getStats();
	}

	function getStats() {
		global $userdata, $frontend;

		$username = (isset($userdata['name']) ? $userdata['name'] : 'not logged in');
		$displayname = (isset($userdata['title']) ? $userdata['title'] : '');
		$language = (isset($userdata['language']) ? $userdata['language'] : 'en_US');
		$memoryUsage = memory_get_usage(false) / 1024;
		$renderTime = microtime(true) - $this->starttime;
		$res = getrusage();

		$debugData = sprintf(
			'[debug]: logged in as %s (@%s) | user time used: %s | system time used: %s | current locale: %s | page rendered in %1.3f secs with %dKB used',
		$displayname, $username, $res["ru_utime.tv_sec"], $res["ru_stime.tv_sec"], $language, $renderTime, $memoryUsage);

		if ($frontend == 'default') {
			printf(
				'<div class="offcanvas offcanvas-bottom text-center show" data-bs-scroll="true" data-bs-backdrop="false" style="visibility:visible;height:unset;">
					<div class="offcanvas-body py-1">%s</div>
				</div>', $debugData);
		} else {
			printf('<center>%s</center>', $debugData);
		}
	}
}