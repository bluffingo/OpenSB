<?php
namespace OpenSB;

global $twig, $orange;

use SquareBracket\Templating;

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Guidelines',
	'file' => 'agpl.md'
]);