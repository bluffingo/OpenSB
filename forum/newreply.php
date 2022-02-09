<?php
require('lib/common.php');

needsLogin();

$action = (isset($_POST['action']) ? $_POST['action'] : null);
$tid = (isset($_GET['id']) ? $_GET['id'] : (isset($_POST['tid']) ? $_POST['tid'] : null));

$thread = fetch("SELECT t.*, f.title ftitle, f.private fprivate, f.readonly freadonly
	FROM z_threads t LEFT JOIN z_forums f ON f.id=t.forum
	WHERE t.id = ? AND t.forum IN " . forumsWithViewPerm(), [$tid]);

if (!$thread) {
	error("404", __("Thread does not exist."));
} else if (!canCreateForumPost(['id' => $thread['forum'], 'private' => $thread['fprivate'], 'readonly' => $thread['freadonly']])) {
	error("403", __("You have no permissions to create posts in this forum!"));
} elseif ($thread['closed'] && !hasPerm('override-closed')) {
	error("400", __("You can't post in closed threads."));
}

$error = '';

if ($action == __("Submit")) {
	$lastpost = fetch("SELECT id,user,date FROM z_posts WHERE thread = ? ORDER BY id DESC LIMIT 1", [$thread['id']]);
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 86400) && !hasPerm('consecutive-posts'))
		$error = __("You can't double post until it's been at least one day!");
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 2) && !hasPerm('consecutive-posts'))
		$error = __("You must wait 2 seconds before posting consecutively.");
	if (strlen(trim($_POST['message'])) == 0)
		$error = __("Your post is empty! Enter a message and try again.");

	if (!$error) {
		query("UPDATE users SET posts = posts + 1, lastpost = ? WHERE id = ?", [time(), $userdata['id']]);
		query("INSERT INTO z_posts (user,thread,date) VALUES (?,?,?)",
			[$userdata['id'],$tid,time()]);
		$pid = insertId();
		query("INSERT INTO z_poststext (id,text) VALUES (?,?)",
			[$pid,$_POST['message']]);
		query("UPDATE z_threads SET replies = replies + 1,lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $tid]);
		query("UPDATE z_forums SET posts = posts + 1,lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $thread['forum']]);

		// nuke entries of this thread in the "threadsread" table
		query("DELETE FROM z_threadsread WHERE tid = ? AND NOT (uid = ?)", [$thread['id'], $userdata['id']]);

		redirect("thread.php?pid=$pid#$pid");
	}
}

$topbot = [
	'breadcrumb' => [
		['href' => './', 'title' => __("Main")], ['href' => "forum.php?id={$thread['forum']}", 'title' => $thread['ftitle']],
		['href' => "thread.php?id={$thread['id']}", 'title' => esc($thread['title'])]
	],
	'title' => __("New reply")
];

$pid = isset($_GET['pid']) ? (int)$_GET['pid'] : 0;
$quotetext = isset($_POST['message']) ? $_POST['message'] : '';
if ($pid) {
	$post = fetch("SELECT u.name name, p.user, pt.text, f.id fid, f.private fprivate, p.thread "
			. "FROM z_posts p "
			. "LEFT JOIN z_poststext pt ON p.id=pt.id "
			. "LEFT JOIN z_poststext pt2 ON pt2.id=pt.id AND pt2.revision=(pt.revision+1) "
			. "LEFT JOIN users u ON p.user=u.id "
			. "LEFT JOIN z_threads t ON t.id=p.thread "
			. "LEFT JOIN z_forums f ON f.id=t.forum "
			. "WHERE p.id = ? AND ISNULL(pt2.id)", [$pid]);

	//does the user have reading access to the quoted post?
	if (!canViewForum(['id' => $post['fid'], 'private' => $post['fprivate']])) {
		$post['name'] = 'ROllerozxa';
		$post['text'] = 'uwu';
	}

	$quotetext = sprintf('[quote="%s" id="%s"]%s[/quote]', $post['name'], $pid, str_replace("&", "&amp;", $post['text']));
}

$post['date'] = time();
$post['text'] = ($action == __("Preview") ? $_POST['message'] : $quotetext);
foreach ($userdata as $field => $val)
	$post['u' . $field] = $val;
$post['ulastpost'] = time();

if ($action == __("Preview")) {
	$topbot['title'] .= __(" (Preview)");
}

$twig = _twigloader();
echo $twig->render('forum/newreply.twig', [
	'post' => $post,
	'topbot' => $topbot,
	'action' => $action,
	'tid' => $tid,
	'error' => $error
]);