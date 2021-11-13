<?php
require('lib/common.php');

$type = (isset($_GET['type']) ? $_GET['type'] : 'all');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$where = ($type != 'all' ? "WHERE v.category_id = ".type_to_cat($type) : '');
$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.time, v.videolength, v.author, v.category_id FROM videos v JOIN users u ON v.author = u.id $where ORDER BY v.id DESC $limit");
$count = result("SELECT COUNT(*) FROM videos v $where");

$twig = twigloader();
echo $twig->render('browse.twig', [
	'type' => $type,
	'levels' => fetchArray($videoData),
	'page' => $page,
	'level_count' => $count
]);