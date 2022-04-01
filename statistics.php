<?php
namespace squareBracket;

require('lib/common.php');

$stats = fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount");

$twig = twigloader();
echo $twig->render('stats.twig', [
	'stats' => $stats
]);