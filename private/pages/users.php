<?php
// ported from principia-web by grkb -4/20/2023
namespace OpenSB;

global $orange;

use SquareBracket\Pages\UserList;
use SquareBracket\Templating;

$page = new UserList($orange);
$data = $page->getData();
$twig = new Templating($orange);

echo $twig->render('users.twig', [
	'users' => $data,
]);
