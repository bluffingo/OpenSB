<?php

/**
 * Return HTML code for an userlink, including stuff like custom colors
 *
 * @param array $user User array containing user fields. Retrieve this from the database using userfields().
 * @param string $prefix $user key prefix.
 * @return string Userlink HTML code.
 */
function userlink($user, $prefix = '') {
	// todo make this a twig component for epicness
	if (isset($user)) {
		return <<<HTML
			<a class="user" href="user.php?name={$user[$prefix.'username']}"><span class="t_user">{$user[$prefix.'username']}</span></a>
HTML;
	} else {
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
function userfields() {
	$fields = ['id', 'username'];

	$out = '';
	foreach ($fields as $field) {
		$out .= sprintf('u.%s u_%s,', $field, $field);
	}

	return $out;
}
