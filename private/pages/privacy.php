<?php
// ported from principia-web by grkb -4/20/2023
namespace OpenSB;

global $orange;

use Orange\Templating;

$twig = new Templating($orange);

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Privacy Policy',
	'file' => 'privacy_policy.md'
]);