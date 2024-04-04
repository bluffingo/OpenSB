<?php
// ported from principia-web by grkb -4/20/2023
namespace OpenSB;

global $twig, $orange;

use SquareBracket\Templating;

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Guidelines',
	'file' => 'guidelines.md'
]);