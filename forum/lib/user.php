<?php

function userfields($tbl = '', $pf = '') {
	$fields = ['id', 'name', 'customcolor'];

	$ret = '';
	foreach ($fields as $f) {
		if ($ret)
			$ret .= ',';
		if ($tbl)
			$ret .= $tbl . '.';
		$ret .= $f;
		if ($pf)
			$ret .= ' ' . $pf . $f;
	}

	return $ret;
}

function userfields_post() {
	$ufields = ['posts','joined','lastpost','lastview','title','avatar','signature'];
	$fieldlist = '';
	foreach ($ufields as $field)
		$fieldlist .= "u.$field u$field,";
	return $fieldlist;
}

function userlink_by_id($uid) {
	$u = fetch("SELECT ".userfields()." FROM users WHERE id=?", [$uid]);
	return userlink($u);
}

function userlinkByName($name) {
	$u = fetch("SELECT ".userfields()." FROM users WHERE UPPER(name)=UPPER(?)", [$name]);
	if ($u)
		return userlink($u, null);
	else
		return 0;
}

function getUsernameLink($matches) {
	$x = str_replace('"', '', $matches[1]);
	$nl = userlinkByName($x);
	if ($nl)
		return $nl;
	else
		return $matches[0];
}