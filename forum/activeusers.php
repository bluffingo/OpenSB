<?php
require('lib/common.php');

$time = (isset($_GET['time']) && is_numeric($_GET['time']) ? $_GET['time'] : 86400);

$users = query("SELECT ".userfields('u').",u.posts,u.joined,COUNT(*) num FROM users u LEFT JOIN z_posts p ON p.user = u.id WHERE p.date > ? GROUP BY u.id ORDER BY num DESC",
	[(time() - $time)]);

$twig = _twigloader();
echo $twig->render('forum/activeusers.twig', [
	'time' => $time,
	'users' => $users
]);