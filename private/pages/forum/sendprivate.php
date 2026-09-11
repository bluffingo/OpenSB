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

$action = $_POST['action'] ?? null;

needsLogin();

if (getUserCountry() == 'GB')
	error(451, "The principia-web PM system is not available for users accessing the website from the United Kingdom, due to the Online Safety Act.".
		(isset($_GET['uid']) && $_GET['uid'] == 1 ? '<br><br>If you wish to contact ROllerozxa, then you may do so through alternate means on his <a href="https://voxelmanip.se/contact/">contact page</a>.' : ''));

$topbot = [
	'breadcrumb' => ["private" => 'Private messages'],
	'title' => 'Send'
];

if (IS_BANNED) error('403', 'You cannot send private messages when banned.');

$userto = $_POST['userto'] ?? '';
$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';

$error = '';

// Submitting a PM
if ($action == 'Submit') {
	$userto = result("SELECT id FROM users WHERE name LIKE ?", [$userto]);

	if (!$userto) $error = "That user doesn't exist.";
	if (!$message) $error = "You can't send a blank message.";

	$recentpms = fetch("SELECT date FROM z_pmsgs WHERE date >= (UNIX_TIMESTAMP() - 30) AND userfrom = ?", [$userdata['id']]);
	if ($recentpms)
		$error = "You can't send more than one PM within 30 seconds!";

	if (!$error) {
		insertInto('z_pmsgs', [
			'date' => time(),
			'userto' => $userto,
			'userfrom' => $userdata['id'],
			'title' => $title,
			'text' => $message
		]);

		$nextId = insertId();
		insertInto('notifications', [
			'type' => 3,
			'level' => $nextId,
			'recipient' => $userto,
			'sender' => $userdata['id']
		]);

		redirect("private");
	}
}

// Default
if (!$action) {
	if (isset($_GET['pid']) && $pid = $_GET['pid']) {
		$post = fetch("SELECT u.name name, p.title, p.text FROM z_pmsgs p LEFT JOIN users u ON p.userfrom = u.id WHERE p.id = ?"
			.(!IS_ROOT ? " AND (p.userfrom=".$userdata['id']." OR p.userto=".$userdata['id'].")" : ''), [$pid]);

		if ($post) {
			$message = sprintf(
				'[reply="%s" id="%s"]%s[/reply]'.PHP_EOL.PHP_EOL,
			$post['name'], $pid, $post['text']);

			$title = 'Re: ' . $post['title'];
			$userto = $post['name'];
		}
	}

	if (isset($_GET['uid']) && $uid = $_GET['uid'])
		$userto = result("SELECT name FROM users WHERE id = ?", [$uid]);
	elseif (!isset($userto))
		$userto = $_POST['userto'];

} elseif ($action == 'Preview') { // Previewing PM
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;

	$post['date'] = time();
	$post['text'] = $_POST['message'];
	$post['headerbar'] = $title.' (Message preview)';

	$topbot['title'] .= ' (Preview)';
}

twigloaderForum()->display('forum/sendprivate.twig', [
	'post' => $post ?? null,
	'userto' => $userto,
	'messagetitle' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'error' => $error
]);
