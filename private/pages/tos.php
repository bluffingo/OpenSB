<?php

namespace OpenSB;

global $twig;

echo $twig->render('_markdown.twig', [
    'pagetitle' => 'Terms of Service',
    'file' => 'terms_of_service.md'
]);