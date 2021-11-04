<?php
require('lib/common.php');

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'posts';
$page = isset($_GET['page']) ? $_GET['page'] : '';
$orderby = isset($_GET['orderby']) ? $_GET['orderby'] : '';

$ppp = 50;
if ($page < 1) $page = 1;

$sortby = ($orderby == 'a' ? " ASC" : " DESC");

$order = 'posts' . $sortby;
if ($sort == 'name') $order = 'name' . $sortby;
if ($sort == 'reg') $order = 'joined' . $sortby;

$users = query("SELECT * FROM users ORDER BY $order LIMIT " . ($page - 1) * $ppp . ",$ppp");
$num = result("SELECT COUNT(*) FROM users");

$pagelist = '';
if ($num >= $ppp) {
	$pagelist = __("Pages:");
	for ($p = 1; $p <= 1 + floor(($num - 1) / $ppp); $p++)
		$pagelist .= ($p == $page ? " $p" : ' ' . mlink($p, $sort, $p, $orderby) . "</a>");
}

$twig = _twigloader();
echo $twig->render('forum/memberlist.twig', [
	'sort' => $sort,
	'page' => $page,
	'orderby' => $orderby,
	'users' => $users,
	'pagelist' => $pagelist
]);