<?php

class Lang {
	private $langData;
	private $langFile;

	function __construct($langFile = '') {
		if ($langFile) {
			$contents = file_get_contents($langFile);
			$this->langData = json_decode($contents, true);
		}

		$this->langFile = $langFile;
	}

	function translate($string, $placeholders = []) {
		if ($this->langFile) {
			if (isset($this->langData[$string])) {
				$translatedString = $this->langData[$string];
			} else {
				$translatedString = $string;
			}
		} else {
			$translatedString = $string;
		}

		return vprintf($translatedString, $placeholders);
	}
}

function __($string, $placeholders = []) {
	global $lang;

	$lang->translate($string, $placeholders);
}