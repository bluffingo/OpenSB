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

$query = trim($_GET['query'] ?? '');
$where = $_GET['where'] ?? 0;

$ufields = userfields('u', 'u');
if ($query && $where == 1) {
	// Search by post text (list threadposts)

	$fieldlist = userfields_post();
	$posts = query("SELECT $ufields, $fieldlist, p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.id tid, t.title ttitle, t.forum tforum
			FROM z_posts p
			JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			JOIN users u ON p.user = u.id
			JOIN z_threads t ON p.thread = t.id
			JOIN z_forums f ON f.id = t.forum
			WHERE pt.text LIKE CONCAT('%', ?, '%') AND ? >= f.minread
			ORDER BY p.id DESC LIMIT 20",
		[$query, $userdata['powerlevel']]);

} elseif ($query) {
	// Search by thread title (list threads)

	$threads = query("SELECT $ufields, t.*
		FROM z_threads t
		JOIN users u ON u.id = t.user
		JOIN z_forums f ON f.id = t.forum
		WHERE t.title LIKE CONCAT('%', ?, '%') AND ? >= f.minread
		ORDER BY t.lastdate DESC",
	[$query, $userdata['powerlevel']]);
}

twigloaderForum()->display('forum/search.twig', [
	'query' => $query,
	'where' => $where,
	'threads' => $threads ?? null,
	'posts' => $posts ?? null
]);
