<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

global $orange;

use \Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/UserList.php';

$page = new \Orange\Pages\UserList($orange);
$data = $page->getData();
$twig = new \Orange\Templating($orange);

echo $twig->render('users.twig', [
	'users' => $data,
]);
