<?php
namespace OpenSB;

global $twig, $orange;

use OpenSB\class\Core\Templating;

echo $twig->render('_markdown.twig', [
	'pagetitle' => 'Guidelines',
	'file' => 'agpl.md'
]);