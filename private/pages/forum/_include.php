<?php

/*
  OpenSB: The Open SquareBracket Software

  Copyright (C) 2026 Chaziz

  This file is based on code from Principia-web.
  Copyright (C) 2020-2026 ROllerozxa

  OpenSB is free software: you can redistribute it and/or modify it under the 
  terms of the GNU Affero General Public License as published by the Free 
  Software Foundation, either version 3 of the License, or (at your option) any
  later version. 

  OpenSB is distributed in the hope that it will be useful, but WITHOUT ANY 
  WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS 
  FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more 
  details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

namespace Pages\Forum;

global $twig, $database, $sb, $auth;

use Core\Utilities;

// opensb, despite originally being based on principia-web, has deviated
// in code design/architecture over the years. this means we need to add
// compatibility code in order to make the principia-web forum codebase
// work on modern-day opensb. -chaziz 09/11/2026

$userdata = $sb->getAuthenticationClass()->getUserData(); 
$log = $sb->getAuthenticationClass()->isLoggedIn();

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
function userfields($tbl = null, $pf = null) {
	$fields = ['id', 'name', 'userlink_color', /*'customcolor', 'trophies'*/];
	$out = [];

	if ($tbl) {
		// Acmlmboard-like
		foreach ($fields as $f)
			$out[] = ($tbl ? $tbl.'.' : '').$f.($pf ? ' '.$pf.$f : '');
	} else {
		// Simpler, principia-web-like
		foreach ($fields as $field)
			$out[] = sprintf('u.%s u_%s', $field, $field);
	}

	return implode(',', $out); //commasep($out);
}

function userfields_post() {
	$fields = [/*'posts',*/ 'joined', /*'avatar', 'signature'*/];
	$out = [];

	foreach ($fields as $f)
		$out[] = "u.$f u$f";

	return implode(',', $out); //commasep($out);
}

/**
 * during opensb development, most usages of this function was replaced with Utilities:notifyBanner.
 */
function error($code, $message = null) {
    Utilities::notifyBanner($message ?? $code . " (no message specified)", "/forum/");
}

function needsLogin() {
    global $auth;

    if (!$auth->isLoggedIn()) {
        Utilities::notifyBanner("notify_login_required", "/login");
    }
}