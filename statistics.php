<?php
require('lib/common.php');

$stats = fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount, (SELECT COUNT(*) FROM music) musiccount, (SELECT COUNT(*) FROM image) imagecount");

$twig = twigloader();
echo $twig->render('stats.twig', [
	'stats' => $stats
]);