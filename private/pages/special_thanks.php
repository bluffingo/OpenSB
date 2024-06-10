<?php

namespace OpenSB;

global $twig;

// special thanks relating exclusively to the squarebracket instance unfortunately won't count.
// -chaziz 6/7/2024

echo $twig->render('_markdown.twig', [
    'pagetitle' => 'Special thanks',
    'file' => 'special_thanks.md'
]);