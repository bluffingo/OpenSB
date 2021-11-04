<?php
require('lib/common.php');

$page = isset($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$fid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$uid = isset($_GET['user']) ? (int)$_GET['user'] : 0;

$fieldlist = userfields('u1', 'u1').",".userfields('u2', 'u2');

if (isset($_GET['id']) && $fid = $_GET['id']) {
	if ($log) {
		$forum = fetch("SELECT f.*, r.time rtime FROM z_forums f LEFT JOIN z_forumsread r ON (r.fid = f.id AND r.uid = ?) "
			. "WHERE f.id = ? AND f.id IN " . forumsWithViewPerm(), [$userdata['id'], $fid]);
		if (!$forum['rtime']) $forum['rtime'] = 0;
	} else
		$forum = fetch("SELECT * FROM z_forums WHERE id = ? AND id IN " . forumsWithViewPerm(), [$fid]);

	if (!isset($forum['id'])) error("404", __("Forum does not exist."));

	$title = $forum['title'];

	$threads = query("SELECT $fieldlist, t.*"
		. ($log ? ", (NOT (r.time<t.lastdate OR isnull(r.time)) OR t.lastdate<'$forum[rtime]') isread" : '') . ' '
		. "FROM z_threads t "
		. "LEFT JOIN users u1 ON u1.id=t.user "
		. "LEFT JOIN users u2 ON u2.id=t.lastuser "
		. ($log ? "LEFT JOIN z_threadsread r ON (r.tid=t.id AND r.uid=$userdata[id])" : '')
		. "WHERE t.forum = ? "
		. "ORDER BY t.sticky DESC, t.lastdate DESC "
		. "LIMIT " . (($page - 1) * $userdata['tpp']) . "," . $userdata['tpp'],
		[$fid]);

	$topbot = [
		'breadcrumb' => [['href' => './', 'title' => __("Main")]],
		'title' => $forum['title']
	];
	if (canCreateForumThread($forum))
		$topbot['actions'] = [['href' => "newthread.php?id=$fid", 'title' => __("New thread")]];
} elseif (isset($_GET['user']) && $uid = $_GET['user']) {
	$user = fetch("SELECT name FROM users WHERE id = ?", [$uid]);

	if (!isset($user)) error("404", __("User does not exist."));

	$title = "Threads by " . $user['name'];

	$threads = query("SELECT $fieldlist, t.*, f.id fid, "
		. ($log ? " (NOT (r.time<t.lastdate OR isnull(r.time)) OR t.lastdate<fr.time) isread, " : ' ')
		. "f.title ftitle FROM z_threads t "
		. "LEFT JOIN users u1 ON u1.id=t.user "
		. "LEFT JOIN users u2 ON u2.id=t.lastuser "
		. "LEFT JOIN z_forums f ON f.id=t.forum "
		. ($log ? "LEFT JOIN z_threadsread r ON (r.tid=t.id AND r.uid=$userdata[id]) "
			. "LEFT JOIN z_forumsread fr ON (fr.fid=f.id AND fr.uid=$userdata[id]) " : '')
		. "WHERE t.user = ? "
		. "AND f.id IN " . forumsWithViewPerm() . " "
		. "ORDER BY t.sticky DESC, t.lastdate DESC "
		. "LIMIT " . (($page - 1) * $userdata['tpp']) . "," . $userdata['tpp'],
		[$uid]);

	$forum['threads'] = result("SELECT count(*) FROM z_threads t "
		. "LEFT JOIN z_forums f ON f.id = t.forum "
		. "WHERE t.user = ? AND f.id IN " . forumsWithViewPerm(), [$uid]);

	$topbot = [
		'breadcrumb' => [['href' => './', 'title' => __("Main")], ['href' => "../user.php?id=$uid", 'title' => $user['name']]],
		'title' => __("Threads")
	];
} elseif ($time = $_GET['time']) {
	$mintime = ($time > 0 && $time <= 2592000 ? time() - $time : 86400);

	$title = __("Latest threats");

	$threads = query("SELECT $fieldlist, t.*, f.id fid,
		f.title ftitle" . ($log ? ', (NOT (r.time<t.lastdate OR isnull(r.time)) OR t.lastdate<fr.time) isread ' : ' ')
		. "FROM z_threads t "
		. "LEFT JOIN users u1 ON u1.id=t.user "
		. "LEFT JOIN users u2 ON u2.id=t.lastuser "
		. "LEFT JOIN z_forums f ON f.id=t.forum "
		. ($log ? "LEFT JOIN z_threadsread r ON (r.tid=t.id AND r.uid=$userdata[id]) "
			. "LEFT JOIN z_forumsread fr ON (fr.fid=f.id AND fr.uid=$userdata[id]) " : '')
		. "WHERE t.lastdate > ? "
		. " AND f.id IN " . forumsWithViewPerm()
		. "ORDER BY t.lastdate DESC "
		. "LIMIT " . (($page - 1) * $userdata['tpp']) . "," . $userdata['tpp'],
	[$mintime]);

	$forum['threads'] = result("SELECT count(*) "
		. "FROM z_threads t "
		. "LEFT JOIN z_forums f ON f.id=t.forum "
		. "WHERE t.lastdate > ? "
		. "AND f.id IN " . forumsWithViewPerm(),
	[$mintime]);

	$topbot = [];
} else {
	error("404", __("Forum does not exist."));
}

$showforum = (isset($time) ? $time : $uid);

if ($forum['threads'] <= $userdata['tpp']) {
	$fpagelist = '';
} else {
	$furl = "forum.php?";
	if ($fid)	$furl .= "id=$fid";
	if ($uid)	$furl .= "user=$uid";
	if ($time)	$furl .= "time=$time";
	$fpagelist = '<br>'.pagelist($forum['threads'], $userdata['tpp'], $furl, $page, true);
}

$twig = _twigloader();
echo $twig->render('forum/forum.twig', [
	'title' => $title,
	'threads' => $threads,
	'showforum' => $showforum,
	'topbot' => $topbot,
	'fpagelist' => $fpagelist,
	'time' => (isset($time) ? $time : null)
]);
