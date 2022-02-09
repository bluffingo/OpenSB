<?php
require('lib/common.php');

needsLogin();

$action = (isset($_POST['action']) ? $_POST['action'] : null);
$fid = (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['fid']) ? $_POST['fid'] : null));

$forum = fetch("SELECT * FROM z_forums WHERE id = ? AND id IN ".forumsWithViewPerm(), [$fid]);

if (!$forum)
	error("404", __("Forum does not exist."));
if (!canCreateForumThread($forum))
	error("403", __("You have no permissions to create threads in this forum!"));

$error = '';

$title = isset($_POST['title']) ? $_POST['title'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

if ($action == __("Submit")) {
	if (strlen(trim($title)) < 0)
		$error = "You need to enter a longer title.";
	if (strlen(trim($message)) == 0)
		$error = __("You need to enter a message to your thread.");
	if ($userdata['lastpost'] > time() - 30 && $action == 'Submit' && !hasPerm('ignore-thread-time-limit'))
		$error = __("Don't post threads so fast, wait a little longer.");
	if ($userdata['lastpost'] > time() - 2 && $action == 'Submit' && hasPerm('ignore-thread-time-limit'))
		$error = __("You must wait 2 seconds before posting a thread.");

	if (!$error) {
		query("UPDATE users SET posts = posts + 1, threads = threads + 1, lastpost = ? WHERE id = ?",
			[time(), $userdata['id']]);

		query("INSERT INTO z_threads (title, forum, user, lastdate, lastuser) VALUES (?,?,?,?,?)",
			[$title, $fid, $userdata['id'], time(), $userdata['id']]);

		$tid = insertId();
		query("INSERT INTO z_posts (user, thread, date) VALUES (?,?,?)",
			[$userdata['id'], $tid, time()]);

		$pid = insertId();
		query("INSERT INTO z_poststext (id, text) VALUES (?,?)", [$pid, $message]);

		query("UPDATE z_forums SET threads = threads + 1, posts = posts + 1, lastdate = ?,lastuser = ?,lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $fid]);

		query("UPDATE z_threads SET lastid = ? WHERE id = ?", [$pid, $tid]);

		redirect("thread.php?id=$tid");
	}
}

$topbot = [
	'breadcrumb' => [['href' => './', 'title' => 'Main'], ['href' => "forum.php?id=$fid", 'title' => $forum['title']]],
	'title' => __("New thread")
];

if ($action == __("Preview")) {
	$post['date'] = time();
	$post['text'] = $_POST['message'];
	foreach ($userdata as $field => $val)
		$post['u' . $field] = $val;
	$post['ulastpost'] = time();

	$topbot['title'] .= __(" (Preview)");

	$title = $_POST['title'];
	$message = $_POST['message'];
}

$twig = _twigloader();
echo $twig->render('forum/newthread.twig', [
	'post' => (isset($post) ? $post : null),
	'title' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'fid' => $fid,
	'error' => $error
]);