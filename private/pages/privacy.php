<?php
// ported from principia-web by chaziz -4/20/2023
namespace OpenSB;

global $twig, $orange;

use SquareBracket\Templating;

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Privacy Policy',
	'file' => 'privacy_policy.md'
]);