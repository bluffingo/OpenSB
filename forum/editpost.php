<?php
require("lib/common.php");

$_GET['act'] = (isset($_GET['act']) ? $_GET['act'] : 'needle');
$_POST['action'] = (isset($_POST['action']) ? $_POST['action'] : '');

if ($action = $_POST['action']) {
	$pid = $_POST['pid'];
} else {
	$pid = $_GET['pid'];
}

if ($_GET['act'] == 'delete' || $_GET['act'] == 'undelete') {
	$action = $_GET['act'];
	$pid = $pid;
}

needsLogin();

$thread = fetch("SELECT p.user puser, t.*, f.title ftitle, f.private fprivate, f.readonly freadonly FROM z_posts p LEFT JOIN z_threads t ON t.id = p.thread "
	."LEFT JOIN z_forums f ON f.id=t.forum WHERE p.id = ? AND (t.forum IN ".forumsWithViewPerm().")", [$pid]);

if (!$thread) $pid = 0;

if ($thread['closed'] && !canCreateForumPosts($thread['forum'])) {
	error("403", __("You can't edit a post in closed threads!"));
} else if (!canEditPost(['user' => $thread['puser'], 'tforum' => $thread['forum']])) {
	error("403", __("You do not have permission to edit this post."));
} else if ($pid == -1) {
	error("404", __("Invalid post ID."));
}

$post = fetch("SELECT u.id, p.user, pt.text FROM z_posts p LEFT JOIN z_poststext pt ON p.id=pt.id "
		."JOIN (SELECT id,MAX(revision) toprev FROM z_poststext GROUP BY id) as pt2 ON pt2.id = pt.id AND pt2.toprev = pt.revision "
		."LEFT JOIN users u ON p.user = u.id WHERE p.id = ?", [$pid]);

if (!isset($post)) $err = __("Post doesn't exist.");

if ($action == __("Submit")) {
	if ($post['text'] == $_POST['message']) {
		error("400", __("No changes detected."));
	}

	$rev = result("SELECT MAX(revision) FROM z_poststext WHERE id = ?", [$pid]) + 1;

	query("INSERT INTO z_poststext (id,text,revision,user,date) VALUES (?,?,?,?,?)", [$pid,$_POST['message'],$rev,$userdata['id'],time()]);

	redirect("thread.php?pid=$pid#edit");
} else if ($action == 'delete' || $action == 'undelete') {

	if (!(canDeleteForumPosts($thread['forum']))) {
		error("403", __("You do not have the permission to do this."));
	} else {
		query("UPDATE z_posts SET deleted = ? WHERE id = ?", [($action == 'delete' ? 1 : 0), $pid]);
		redirect("thread.php?pid=$pid#edit");
	}
}

$topbot = [
	'breadcrumb' => [
		['href' => './', 'title' => 'Main'],
		['href' => "forum.php?id={$thread['forum']}", 'title' => $thread['ftitle']],
		['href' => "thread.php?id={$thread['id']}", 'title' => esc($thread['title'])]
	],
	'title' => __("Edit post")
];

$euser = fetch("SELECT * FROM users WHERE id = ?", [$post['id']]);
$post['date'] = time();
$post['text'] = ($action == __("Preview") ? $_POST['message'] : $post['text']);
foreach ($euser as $field => $val)
	$post['u'.$field] = $val;
$post['ulastpost'] = time();

if ($action == __("Preview")) {
	$topbot['title'] .= __(" (Preview)");
}

$twig = _twigloader();
echo $twig->render("forum/editpost.twig", [
	'post' => $post,
	'topbot' => $topbot,
	'action' => $action,
	'pid' => $pid
]);