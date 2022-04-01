<?php
namespace squareBracket;
class profiler {
	private $starttime;

	function __construct() {
		$this->starttime = microtime(true);
	}

	function __destruct() {
		$this->getStats();
	}

	function getStats() {
		global $userdata;

		if (isCli()) return;
		$headers = headers_list();
		
		foreach($headers as $index => $value) {
			list($key, $value) = explode(': ', $value);
			
			unset($headers[$index]);
			
			$headers[$key] = $value;
		}
		//check if we are outputing html. if not, simply abort.
		if (isset($headers["Content-Type"])) {
			if ($headers["Content-Type"] != "text/html") return;
		}

		$username = (isset($userdata['name']) ? $userdata['name'] : 'not logged in');
		$displayname = (isset($userdata['title']) ? $userdata['title'] : '');
		$language = (isset($userdata['language']) ? $userdata['language'] : 'en_US');
		$memoryUsage = memory_get_usage(false) / 1024;
		$renderTime = microtime(true) - $this->starttime;
		$res = getrusage();

		$debugData = sprintf(
			'[debug]: logged in as %s (@%s) | user time used: %s | system time used: %s | current locale: %s | page rendered in %1.3f secs with %dKB used',
		$displayname, $username, $res["ru_utime.tv_sec"], $res["ru_stime.tv_sec"], $language, $renderTime, $memoryUsage);

		print('<div class="footer" style="position:fixed;bottom:0;width:100%"><center>'.$debugData.'</center></div>');
	}
}