<?php
require('lib/common.php');
use ScssPhp\ScssPhp\Compiler;

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
}

$customProfile = fetch("SELECT * FROM channel_settings WHERE user = ?", [$userpagedata['id']]);

var_dump($customProfile);

if ($customProfile == false) {
	query("INSERT INTO `channel_settings` 
	(`user`, `background`, `fontcolor`, `titlefont`, `link`, `headerfont`, `highlightheader`, `highlightinside`, `regularheader`, `regularinside`) 
	VALUES (?, '#ffffff', '#222222', '#ffffff', '#0033CC', '#ffffff', '#3399cc', '#ecf4fb', '#3399cc', '#ffffff')",[$userpagedata['id']]);
}

if (!isset($userpagedata) || !$userpagedata) {
	error('404', 'No user specified');
}

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
$forceuser = isset($_GET['forceuser']);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$latestVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.tags FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY v.id DESC LIMIT 15", [$userpagedata['id']]);
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

$subCount = fetch("SELECT COUNT(user) FROM subscriptions WHERE user = ?", [$userpagedata['id']])['COUNT(user)'];

$scss = new Compiler();
$scss->setImportPaths($_SERVER['DOCUMENT_ROOT']);
$css = $scss->compile(
	'$color: '.$userpagedata['customcolor'].';
	@mixin gradient-y-three-colors($start-color: $blue, $mid-color: $purple, $color-stop: 50%, $end-color: $red) {
		background-image: linear-gradient($start-color, $mid-color $color-stop, $end-color);
	}
	@mixin text-contrast($n) {
		$color-brightness: round((red($n) * 299) + (green($n) * 587) + (blue($n) * 114) / 1000);
		$light-color: round((red(#ffffff) * 299) + (green(#ffffff) * 587) + (blue(#ffffff) * 114) / 1000);

		@if abs($color-brightness) < ($light-color/2){
			color: white;
		}

		@else {
			color: black;
		}
	}
	.bg-custom-profile {
		@include gradient-y-three-colors(darken($color, 0%), darken($color, 7%), 50%, darken($color, 15%));
		@include text-contrast($color);
	}
	.bg-primary {
		@include gradient-y-three-colors(lighten($color, 8%), $color, 60%, darken($color, 4%));
	}'
);

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
	'comments' => (isset($comments) ? $comments : null),
	'subCount' => $subCount,
	'customProfile' => $customProfile
]);