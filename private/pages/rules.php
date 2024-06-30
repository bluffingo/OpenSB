<?php

namespace OpenSB;

global $twig;

echo $twig->render('_markdown.twig', [
    'pagetitle' => 'Rules',
    'file' => 'rules.md'
]);