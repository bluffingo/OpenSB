<?php

/**
 * Return HTML code for an userlink.
 *
 * @param array $user User array containing user fields. Retrieve this from the database using userfields().
 * @param string $prefix $user key prefix.
 * @return string Userlink HTML code.
 */
 
function userlink($user, $pre = '') {
	global $acmlm;

	if ($user[$pre.'customcolor']) {
		$user[$pre.'colorname'] = sprintf('<span style="color:%s">%s</span>', $user[$pre.'customcolor'], $user[$pre.'name']);
	}

	$htmlrelpathfuckery = (isset($acmlm) ? '../' : '');

	return <<<HTML
		<a class="user" href="{$htmlrelpathfuckery}user.php?name={$user[$pre.'name']}"><span class="t_user">{$user[$pre.'colorname']}</span></a>
HTML;
}

if (!isset($acmlm)) {

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
function userfields() {
	$fields = ['id', 'name', 'customcolor', 'joined'];

	$out = '';
	foreach ($fields as $field) {
		$out .= sprintf('u.%s u_%s,', $field, $field);
	}

	return $out;
}

}