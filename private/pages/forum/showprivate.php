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

include_once('_include.php');

needsLogin();

if (getUserCountry() == 'GB')
	error(451, "The principia-web PM system is not available for users accessing the website from the United Kingdom, due to the Online Safety Act.");

$fieldlist = userfields('u', 'u').','.userfields_post().',';

$pid = $_GET['id'] ?? null;

$pmsg = fetch("SELECT $fieldlist p.* FROM z_pmsgs p LEFT JOIN users u ON u.id = p.userfrom WHERE p.id = ?", [$pid]);
if (!$pmsg) error('404');
$tologuser = ($pmsg['userto'] == $userdata['id']);

if ((!$tologuser && $pmsg['userfrom'] != $userdata['id']) && !IS_ROOT)
	error('404');
elseif ($tologuser && $pmsg['unread']) {
	query("UPDATE z_pmsgs SET unread = 0 WHERE id = ?", [$pid]);
	query("DELETE FROM notifications WHERE type = 3 AND level = ? AND recipient = ?", [$pid, $userdata['id']]);
}

$pagebar = [
	'breadcrumb' => ["private" => 'Private messages'],
	'title' => $pmsg['title'] ?: '(untitled)',
	'actions' => ["sendprivate?pid=$pid" => 'Reply']
];

$pmsg['id'] = 0;

echo $twig->render('forum/showprivate.twig', [
	'pagebar' => $pagebar,
	'pmsg' => $pmsg
]);
