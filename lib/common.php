<?php
if (!file_exists('conf/config.php')) {
	die('Please read the installing instructions in the README file.');
}

require('conf/config.php');
require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}
