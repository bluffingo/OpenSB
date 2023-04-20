<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$users = $sql->fetchArray($sql->query("SELECT u.id, u.name, u.customcolor, u.joined, (SELECT COUNT(*) FROM videos WHERE author = u.id) AS s_num FROM users u"));

echo twigloader()->render('userlist.twig', [
	'users' => $users,
]);
