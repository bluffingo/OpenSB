<?php

/**
 * Return HTML code for an userlink.
 *
 * @param array $user User array containing user fields. Retrieve this from the database using userfields().
 * @param string $prefix $user key prefix.
 * @return string Userlink HTML code.
 */
 
 // todo: use display name instead of username -gr 8/2/2021
function userlink($user, $pre = '') {
	global $acmlm;

	//if ($user[$pre.'id'] == 1) {
	//	$user[$pre.'name'] = '<span style="color:#D60270">ROll</span><span style="color:#9B4F96">er</span><span style="color:#0038A8">ozxa</span>';
	//}

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
	$fields = ['id', 'name', 'customcolor'];

	$out = '';
	foreach ($fields as $field) {
		$out .= sprintf('u.%s u_%s,', $field, $field);
	}

	return $out;
}

}