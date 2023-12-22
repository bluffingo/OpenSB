<?php
// ported from principia-web by grkb -4/20/2023
namespace openSB;

global $orange;
require_once dirname(__DIR__) . '/private/class/common.php';

$twig = new \Orange\Templating($orange);

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Privacy Policy',
	'file' => 'privacy_policy.md'
]);