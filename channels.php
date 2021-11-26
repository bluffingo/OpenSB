<?php
require('lib/common.php');

$limit = 40;

$offset = ((isset($_GET['page']) ? $_GET['page'] : 1) - 1) * $limit;

// currently selects all registered users (channels)
$userData = query("SELECT name, lastview FROM users ORDER BY lastview DESC LIMIT ? OFFSET ?", [$limit, $offset]);

$pageCount = ceil(fetch("SELECT COUNT(*) FROM users")['COUNT(*)'] / $limit);
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/channels.php?page=';

$twig = twigloader();

echo $twig->render('channels.twig', [
	'users' => $userData,
	'currentPage' => (isset($_GET['page']) ? $_GET['page'] : 1),
	'pageCount' => $pageCount,
	'url' => $url
]);
