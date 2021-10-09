<?php

/**
 * Return HTML code for an userlink.
 *
 * @param array $user User array containing user fields. Retrieve this from the database using userfields().
 * @param string $prefix $user key prefix.
 * @return string Userlink HTML code.
 */
 
 // todo: use display name instead of username -gr 8/2/2021
function userlink($user, $prefix = '') {
	if (isset($user)) {
			return <<<HTML
			<a class="user" href="user.php?name={$user[$prefix.'username']}">{$user[$prefix.'username']}</a>
HTML;
		}
	 else {
		return <<<HTML
		No data supplied!
HTML;
	}
}

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
if(!isset($acmlm)) {
function userfields() {
	$fields = ['id', 'username'];

	$out = '';
	foreach ($fields as $field) {
		$out .= sprintf('u.%s u_%s,', $field, $field);
	}

	return $out;
}
}