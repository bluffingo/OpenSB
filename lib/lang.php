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
			$translatedString = $this->langData[$string];

			if ($translatedString == "") {
				$translatedString = $string;
			}
		} else {
			$translatedString = $string;
		}

		return vsprintf($translatedString, $placeholders);
	}
}
