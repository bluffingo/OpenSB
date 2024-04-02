<?php
namespace OpenSB;

global $orange;

use SquareBracket\Templating;

$twig = new Templating($orange);

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Guidelines',
	'file' => 'agpl.md'
]);