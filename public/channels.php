<?php

namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

$limit = 40;

$offset = (($_GET['page'] ?? 1) - 1) * $limit;

// currently selects all registered users (channels)
$userData = $sql->query("SELECT name, lastview FROM users ORDER BY lastview DESC LIMIT ? OFFSET ?", [$limit, $offset]);

$pageCount = ceil($sql->fetch("SELECT COUNT(*) FROM users")['COUNT(*)'] / $limit);
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/channels.php?page=';

$twig = twigloader();

echo $twig->render('channels.twig', [
    'users' => $userData,
    'currentPage' => ($_GET['page'] ?? 1),
    'pageCount' => $pageCount,
    'url' => $url
]);
