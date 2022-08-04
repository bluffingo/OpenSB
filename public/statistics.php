<?php

namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

$stats = fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount");

$twig = twigloader();
echo $twig->render('stats.twig', [
    'stats' => $stats
]);