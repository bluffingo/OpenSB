<?php

namespace OpenSB;

global $orange;

use SquareBracket\Pages\Version;
use SquareBracket\Templating;

$page = new Version($orange);
$twig = new Templating($orange);

echo $twig->render('version.twig', [
    'data' => $page->getData(),
]);