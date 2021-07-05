<?php
if (!file_exists('conf/config.php')) {
	die('Welcome to your squareBracket envirorment. A config file could\'nt be found. Please read the installing instructions in the README file.');
}

require('conf/config.php');
require('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}
