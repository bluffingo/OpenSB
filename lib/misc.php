<?php

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