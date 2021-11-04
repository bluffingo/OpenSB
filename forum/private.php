<?php
require('lib/common.php');

needsLogin();

$page = (isset($_GET['page']) ? $_GET['page'] : null);
if (!$page) $page = 1;
$view = (isset($_GET['view']) ? $_GET['view'] : 'read');

if ($view == 'sent') {
	$fieldn = 'to';
	$fieldn2 = 'from';
	$sent = true;
} else {
	$fieldn = 'from';
	$fieldn2 = 'to';
	$sent = false;
}

$id = (isset($_GET['id']) ? $_GET['id'] : null);

$showdel = isset($_GET['showdel']);

if (isset($_GET['action']) && $_GET['action'] == "del") {
	$owner = result("SELECT user$fieldn2 FROM z_pmsgs WHERE id = ?", [$id]);
	if (hasPerm('delete-user-pms') || ($owner == $userdata['id'] && hasPerm('delete-own-pms'))) {
		query("UPDATE z_pmsgs SET del_$fieldn2 = ? WHERE id = ?", [!$showdel, $id]);
	} else {
		error("403", __("You are not allowed to (un)delete that message."));
	}
	$id = 0;
}

$ptitle = __("Private messages") . ($sent ? __(" (sent)") : '');
if ($id && hasPerm('view-user-pms')) {
	$user = fetch("SELECT id,name,group_id FROM users WHERE id = ?", [$id]);
	if ($user == null) error("404", __("User doesn't exist."));
	$headtitle = $user['name']."'s ".strtolower($ptitle);
	$title = userlink($user)."'s ".strtolower($ptitle);
} else {
	$id = $userdata['id'];
	$headtitle = $ptitle;
	$title = $ptitle;
}

$pmsgc = result("SELECT COUNT(*) FROM z_pmsgs WHERE user$fieldn2 = ? AND del_$fieldn2 = ?", [$id, $showdel]);
$pmsgs = query("SELECT ".userfields('u', 'u').", p.* FROM z_pmsgs p "
					."LEFT JOIN users u ON u.id = p.user$fieldn "
					."WHERE p.user$fieldn2 = ? "
				."AND del_$fieldn2 = ? "
					."ORDER BY p.unread DESC, p.date DESC "
					."LIMIT " . (($page - 1) * $userdata['tpp']) . ", " . $userdata['tpp'],
				[$id, $showdel]);

$topbot = [
	'breadcrumb' => [['href' => './', 'title' => __("Main")]],
	'title' => $title
];

if ($sent)
	$topbot['actions'] = [['href' => 'private.php'.($id != $userdata['id'] ? "?id=$id&" : ''), 'title' => __("View received")]];
else
	$topbot['actions'] = [['href' => 'private.php?'.($id != $userdata['id'] ? "id=$id&" : '').'view=sent', 'title' => __("View sent")]];

$topbot['actions'][] = ['href' => 'sendprivate.php', 'title' => __("Send new")];

if ($pmsgc <= $userdata['tpp'])
	$fpagelist = '<br>';
else {
	if ($id != $userdata['id'])
		$furl = "private.php?id=$id&view=$view";
	else
		$furl = "private.php?view=$view";
	$fpagelist = pagelist($pmsgc, $userdata['tpp'], $furl, $page).'<br>';
}

$twig = _twigloader();
echo $twig->render('forum/private.twig', [
	'id' => $id,
	'pmsgs' => $pmsgs,
	'topbot' => $topbot,
	'fieldn' => $fieldn,
	'fpagelist' => $fpagelist,
	'headtitle' => $headtitle
]);
