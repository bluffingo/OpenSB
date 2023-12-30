<?php

namespace OpenSB;

global $orange;

use Orange\Templating;
use Orange\Pages\Version;

$page = new Version($orange);
$twig = new Templating($orange);

echo $twig->render('version.twig', [
    'data' => $page->getData(),
]);