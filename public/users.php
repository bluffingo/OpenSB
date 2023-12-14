<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

global $betty;

use \Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/private/class/Pages/Users.php';

$page = new \Orange\Pages\Users($betty);
$data = $page->getData();
$twig = new \Orange\Templating($betty);

echo $twig->render('users.twig', [
	'users' => $data,
]);
