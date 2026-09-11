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

$act = $_GET['act'] ?? '';
$action = $_POST['action'] ?? '';

$pid = $_GET['pid'] ?? null;

if ($act == 'delete' || $act == 'undelete') {
	if (!IS_MOD)
		error('403', "You do not have the permission to do this.");

	query("UPDATE z_posts SET deleted = ? WHERE id = ?", [($act == 'delete' ? 1 : 0), $pid]);
	redirect("thread?pid=$pid#$pid");
}

needsLogin();

$thread = fetch("SELECT p.user puser, t.*, f.title ftitle
			FROM z_posts p
			LEFT JOIN z_threads t ON t.id = p.thread
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE p.id = ? AND ? >= f.minread",
		[$pid, $userdata['rank']]);

if (!$thread)
	error('404');
if ($thread['closed'] && !IS_MOD)
	error('403', "You can't edit a post in closed threads!");
if (!IS_ROOT && $userdata['id'] != $thread['puser'])
	error('403', "You do not have permission to edit this post.");

$editpost = fetch("SELECT u.id, p.user, pt.text
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id WHERE p.id = ?",
		[$pid]);

$message = $_POST['message'] ?? $editpost['text'];

if ($action == 'Submit') {
	if ($editpost['text'] == $_POST['message'])
		error('400', "No changes detected.");

	$newrev = result("SELECT revision FROM z_posts WHERE id = ?", [$pid]) + 1;

	query("UPDATE z_posts SET revision = ? WHERE id = ?", [$newrev, $pid]);

	insertInto('z_poststext', [
		'id' => $pid,
		'text' => $_POST['message'],
		'revision' => $newrev,
		'date' => time()
	]);

	redirect("thread?pid=$pid#$pid");
}

$topbot = [
	'breadcrumb' => [
		"forum?id={$thread['forum']}" => $thread['ftitle'],
		"thread?id={$thread['id']}" => $thread['title']],
	'title' => 'Edit post'
];

if ($action == 'Preview') {
	$euser = fetch("SELECT * FROM users WHERE id = ?", [$editpost['id']]);
	foreach ($euser as $field => $val)
		$post['u'.$field] = $val;

	$post['text'] = $message;
	$post['date'] = time();
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

twigloaderForum()->display('forum/editpost.twig', [
	'post' => $post ?? null,
	'topbot' => $topbot,
	'action' => $action,
	'pid' => $pid,
	'message' => $message
]);
