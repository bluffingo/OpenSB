<?php

namespace squareBracket;

use WhiteCube\Lingua\Service as Lingua;

require_once(dirname(__DIR__) . "/class/misc.php");

if (!isCli()) {
	$dir = new \DirectoryIterator('../private/lang/');
	foreach ($dir as $file) {
		if ($file->getFilename() != "template.json" and $file->getFilename() != "qps-plocm.json") {
			if ($file->getExtension() == "json") {
				$language = Lingua::createFromW3C($file->getBasename('.json'));
				$languages[$file->getBasename('.json')] = $language->toName();
			}
		}
	}

	if ($isDebug) {
		$languages['qps-plocm'] = "Pseudolocalization";
	}
}