<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

global $betty;

use \Betty\BettyException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/Pages/Users.php';

$page = new \Betty\Pages\Users($betty);
$data = $page->getData();
$twig = new \Betty\Templating($betty);

echo $twig->render('users.twig', [
	'users' => $data,
]);
