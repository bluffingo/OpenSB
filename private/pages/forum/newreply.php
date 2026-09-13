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

use Core\Utilities;

include_once('_include.php');

global $sb, $database, $twig;

needsLogin();

$action = $_POST['action'] ?? null;
$tid = $_GET['id'] ?? null;

$thread = $database->fetch("SELECT t.*, f.title ftitle, f.minreply fminreply
	FROM z_threads t LEFT JOIN z_forums f ON f.id=t.forum
	WHERE t.id = ? AND ? >= f.minread", [$tid, $userdata['powerlevel']]);

if (!$thread)
	error('404');
if ($thread['fminreply'] > $userdata['powerlevel'])
	error('403', "You have no permissions to create posts in this forum!");
if ($thread['closed'] /*&& !IS_MOD*/)
	error('400', "You can't post in closed threads.");

$error = '';

$message = $_POST['message'] ?? '';

if ($action == 'Submit') {
	$lastpost = $database->fetch("SELECT id,user,date FROM z_posts WHERE thread = ? ORDER BY id DESC LIMIT 1", [$thread['id']]);
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 86400) /*&& !IS_ADMIN*/)
		$error = "You can't double post until it's been at least one day!";
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 2) /*&& IS_ADMIN*/)
		$error = "You must wait 2 seconds before posting consecutively.";
	if (strlen(trim($message)) == 0)
		$error = "Your post is empty! Enter a message and try again.";
	if (strlen(trim($message)) < 25)
		$error = "Your post is too short to be meaningful. Please try to write something longer.";

	if (!$error) {
		$pid = newPost([
			'thread' => $tid,
			'forum' => $thread['forum'],
			'title' => $thread['title'],
			'message' => $message,
			'u_id' => $userdata['id'],
			'u_name' => $userdata['name']
		]);

		Utilities::redirect("thread?pid=$pid#$pid");
	}
}

$topbot = [
	'breadcrumb' => [
		"forum?id={$thread['forum']}" => $thread['ftitle'],
		"thread?id={$thread['id']}" => $thread['title']],
	'title' => "New reply"
];

$pid = $_GET['pid'] ?? 0;
if ($pid) {
	$post = $database->fetch("SELECT u.name name, p.user, pt.text, f.id fid, p.thread, f.minread
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			LEFT JOIN z_threads t ON t.id = p.thread
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE p.id = ?", [$pid]);

	//does the user have reading access to the quoted post?
	if ($userdata['powerlevel'] < $post['minread']) {
		$post['name'] = 'ROllerozxa';
		$post['text'] = 'uwu';
	}

	$message = sprintf(
		'[quote="%s" id="%s"]%s[/quote]'.PHP_EOL.PHP_EOL,
	$post['name'], $pid, $post['text']);
}

if ($action == 'Preview') {
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;

	$post['date'] = time();
	$post['text'] = $message;
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

$fieldlist = userfields('u', 'u') . ','; /*.u.posts uposts, ';*/

$newestposts = $database->query("SELECT $fieldlist p.*, pt.text
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			WHERE p.thread = ? AND p.deleted = 0
			ORDER BY p.id DESC LIMIT 5", [$tid]);

echo $twig->render('forum/newreply.twig', [
	'post' => $post ?? null,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'tid' => $tid,
	'error' => $error,
	'newestposts' => $newestposts
]);
