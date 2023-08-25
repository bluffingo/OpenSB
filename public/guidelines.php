<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

global $betty;
require_once dirname(__DIR__) . '/private/class/common.php';

$twig = new \Orange\Templating($betty);

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Guidelines',
	'file' => 'guidelines.md'
]);