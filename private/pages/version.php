<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\Version;
use SquareBracket\Templating;

$page = new Version($orange);

echo $twig->render('version.twig', [
    'data' => $page->getData(),
]);