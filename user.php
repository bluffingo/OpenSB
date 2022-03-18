<?php
require('lib/common.php');
use ScssPhp\ScssPhp\Compiler;

$message = '';

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
	if (!isset($userpagedata) || !$userpagedata) {
		error('404', 'Invalid user');
	}
	$customProfile = fetch("SELECT * FROM channel_settings WHERE user = ?", [$userpagedata['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
	if (!isset($userpagedata) || !$userpagedata) {
		error('404', 'Invalid user');
	}
	$customProfile = fetch("SELECT * FROM channel_settings WHERE user = ?", [$userpagedata['id']]);
} else {
    error('404', 'No user specified');
}

// var_dump($customProfile);

if ($customProfile == false) {
	query("INSERT INTO `channel_settings` 
	(`user`, `background`, `fontcolor`, `titlefont`, `link`, `headerfont`, `highlightheader`, `highlightinside`, `regularheader`, `regularinside`) 
	VALUES (?, '#ffffff', '#222222', '#ffffff', '#0033CC', '#ffffff', '#3399cc', '#ecf4fb', '#3399cc', '#ffffff')",[$userpagedata['id']]);
}

$page = (isset($_GET['p']) && is_numeric($_GET['p']) && $_GET['p'] > 0 ? $_GET['p'] : 1);
$forceuser = isset($_GET['forceuser']);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$latestVideoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.tags FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY v.id DESC LIMIT 9", [$userpagedata['id']]);
$latestVideo  = fetch("SELECT $userfields v.*, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.tags FROM videos v JOIN users u ON v.author = u.id WHERE v.author = ? ORDER BY v.id DESC", [$userpagedata['id']]);
$count = result("SELECT COUNT(*) FROM videos l WHERE l.author = ?", [$userpagedata['id']]);

$commentData = query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM channel_comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$userpagedata['id']]);

$subCount = fetch("SELECT COUNT(user) FROM subscriptions WHERE user = ?", [$userpagedata['id']])['COUNT(user)'];
$subscribers  = query("SELECT $userfields s.* FROM subscriptions s JOIN users u on user WHERE s.user = ?", [$userpagedata['id']]);
$totalViews = result("SELECT SUM(views) FROM videos WHERE author = ?", [$userpagedata['id']]);

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

if ( isset( $log ) && !empty( $log ) ) {
	$subscribed = result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$userdata['id'], $userpagedata['id']]);
} else {
	$subscribed = 0;
}

//fixes depreciation warning
if ($userpagedata['about'] == null) {
	$userpagedata['about'] = '';
}

$twig = twigloader();
echo $twig->render("user.twig", [
	'id' => $userpagedata['id'],
	'name' => $userpagedata['name'],
	'userpagedata' => $userpagedata,
	'latestVideos' => $latestVideoData,
	'video' => $latestVideo,
	'forceuser' => $forceuser,
	'page' => $page,
	'level_count' => $count,
	'notifs' => (isset($notifications) ? $notifications : []),
	'markread' => (isset($_GET['markread']) ? true : false),
	'edited' => (isset($_GET['edited']) ? true : false),
	'comments' => (isset($comments) ? $comments : null),
	'subCount' => $subCount,
	'subscribed' => $subscribed,
	'customProfile' => $customProfile,
	'comments' => $commentData,
	'message' => $message,
	'subscribers' => $subscribers,
	'views' => $totalViews,
]);