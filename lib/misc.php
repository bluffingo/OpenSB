<?php

/**
 * Returns true if it is executed from the command-line. (For command-line tools)
 */
function isCli() {
	return php_sapi_name() == "cli";
}

// Makes incomplete unready features not available on production (aka squarebracket.veselcraft.ru)
function notReady() {
	http_response_code(403);
	die(__("This feature is not ready for production."));
}

function accessDenied() {
	http_response_code(403);
	die(__("Access Denied"));
}

/**
 * Get hash of latest git commit
 *
 * @param bool $trim Trim the hash to the first 7 characters
 * @return void
 */
function gitCommit($trim = true) {
	$commit = file_get_contents('.git/refs/heads/main');

	if ($trim)
		return substr($commit, 0, 7);
	else
		return rtrim($commit);
}