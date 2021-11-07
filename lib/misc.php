<?php

/**
 * Returns true if it is executed from the command-line. (For command-line tools)
 */
function isCli() {
	return php_sapi_name() == "cli";
}

function accessDenied() {
	http_response_code(403);
	die(__("Access Denied"));
}

function redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
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
