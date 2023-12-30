<?php
// ported from principia-web by grkb -4/20/2023
namespace OpenSB;

global $orange;

use Orange\Templating;
use Orange\Pages\UserList;

$page = new UserList($orange);
$data = $page->getData();
$twig = new Templating($orange);

echo $twig->render('users.twig', [
	'users' => $data,
]);
