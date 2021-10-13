<?php
require('lib/common.php');

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
}

if (!isset($userpagedata) || !$userpagedata) {
	error('No user specified');
}

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
$forceuser = isset($_GET['forceuser']);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$latestVideoData = query("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY v.id DESC LIMIT 15", [$userpagedata['id']]);
$count = result("SELECT COUNT(*) FROM videos l WHERE l.author = ?", [$userpagedata['id']]);

// Personal user page stuff
if (isset($userdata['id']) && $userdata['id'] == $userpagedata['id'] && !$forceuser) {
	if (isset($_GET['markread'])) {
		query("DELETE FROM notifications WHERE recipient = ?", [$userdata['id']]);
		$notificationCount = 0;
	}

	$notifsdata = query("SELECT $userfields n.*, l.id l_id, l.title l_title FROM notifications n LEFT JOIN videos l ON n.level = l.id JOIN users u ON n.sender = u.id WHERE n.recipient = ?", [$userdata['id']]);

	$notifications = [];
	while ($notifdata = $notifsdata->fetch()) {
		switch ($notifdata['type']) {
			case 1:
				$notifications[] = sprintf('%s commented on <a href="watch.php?id=%s">%s</a>.', userlink($notifdata, 'u_'), $notifdata['l_id'], $notifdata['l_title']);
			break;
			case 2:
				$notifications[] = sprintf('%s commented on your <a href="user.php?id=%s&forceuser">user page</a>.', userlink($notifdata, 'u_'), $userdata['id']);
			break;
			case 3:
				$notifications[] = sprintf('%s sent you a private message: <a href="forum/showprivate.php?id=%s">Read</a>', userlink($notifdata, 'u_'), $notifdata['level']);
			break;
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
				$notifications[] = sprintf(
					'%s mentioned you in a %s comment: <a href="%s.php?id=%s">Read</a>',
				userlink($notifdata, 'u_'), cmtNumToType($notifdata['type'] - 10), cmtNumToType($notifdata['type'] - 10), $notifdata['level']);
			break;
		}
	}
} else { // general profile details stuff
	if ($userpagedata['about']) {
		$markdown = new Parsedown();
		$markdown->setSafeMode(true);
		$userpagedata['about'] = $markdown->text($userpagedata['about']);
	}

	//not implemented
	//$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 4 AND c.level = ? ORDER BY c.time DESC", [$userpagedata['id']]);

	if (isset($userdata['id']) && $userpagedata['id'] == $userdata['id']) {
		query("DELETE FROM notifications WHERE type = 2 AND recipient = ?", [$userdata['id']]);
	}

	//clearMentions('user', $userpagedata['id']);
}

$twig = twigloader();
echo $twig->render('user.twig', [
	'id' => $userpagedata['id'],
	'name' => $userpagedata['name'],
	'userpagedata' => $userpagedata,
	'latestVideos' => $latestVideoData,
	'forceuser' => $forceuser,
	'page' => $page,
	'level_count' => $count,
	'notifs' => (isset($notifications) ? $notifications : []),
	'markread' => (isset($_GET['markread']) ? true : false),
	'edited' => (isset($_GET['edited']) ? true : false),
	'comments' => (isset($comments) ? $comments : null)
]);