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

use Core\Utilities;

global $sb, $database, $twig;

$action = $_GET['action'] ?? '';

$userdata = $sb->getAuthenticationClass()->getUserData(); 
$log = $sb->getAuthenticationClass()->isLoggedIn();

$categ = [];

//mark forum read
if ($log && $action == 'markread') {
	$fid = $_GET['fid'];
	if ($fid != 'all') {
		//delete obsolete threadsread entries
		$database->query("DELETE r FROM z_threadsread r LEFT JOIN z_threads t ON t.id = r.tid WHERE t.forum = ? AND r.uid = ?", [$fid, $userdata['id']]);
		//add new forumsread entry
		$database->query("REPLACE INTO z_forumsread VALUES (?,?,?)", [$userdata['id'], $fid, time()]);
	} else {
		//mark all read
		$database->query("DELETE FROM z_threadsread WHERE uid = ?", [$userdata['id']]);
		$database->query("REPLACE INTO z_forumsread (uid,fid,time) SELECT ?, f.id, ? FROM z_forums f", [$userdata['id'], time()]);
	}
	Utilities::redirect('/forum/');
}

$categs = $database->query("SELECT id,title FROM z_categories ORDER BY ord,id");
while ($c = $categs->fetch())
	$categ[$c['id']] = $c['title'];

$forums = $database->query("SELECT f.*, ".($log ? "r.time rtime, " : '').userfields('u', 'u')." "
		. "FROM z_forums f "
		. "LEFT JOIN users u ON u.id=f.lastuser "
		. "LEFT JOIN z_categories c ON c.id=f.cat "
		. ($log ? "LEFT JOIN z_forumsread r ON r.fid = f.id AND r.uid = ".$userdata['id'] : '')
		. " WHERE ? >= f.minread "
		. " ORDER BY c.ord,c.id,f.ord,f.id ",
        // on principia-web, "powerlevel" was renamed to "rank" (commit 4073bc1), this change was never applied to opensb.
		[$userdata['powerlevel']]); //[$userdata['rank']]);

echo $twig->render('forum/index.twig', [
	'forums' => $forums,
	'categories' => $categ
]);
