<?php

// TODO: Merge this with orange's common.php.

namespace openSB;

if (!file_exists(dirname(__DIR__) . '/conf/config.php')) {
    die('<b>A configuration file could not be found. Please read the installing instructions in the README file.</b>');
}

require_once(dirname(__DIR__) . '/conf/config.php');

require_once(dirname(__DIR__) . '/../vendor/autoload.php'); //dogshit

// aaaa psr-4 autoload!!! - rgb

// load the orange stuff
require_once(dirname(__DIR__) . "/../orange/common.php");