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

$time = (int)($_GET['time'] ?? 86400);

$ufields = userfields('u');
$users = query("SELECT $ufields,u.posts,u.joined,COUNT(*) num FROM users u LEFT JOIN z_posts p ON p.user = u.id WHERE p.date > ? GROUP BY u.id ORDER BY num DESC",
	[(time() - $time)]);

twigloaderForum()->display('forum/activeusers.twig', [
	'time' => $time,
	'users' => $users
]);
