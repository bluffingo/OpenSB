<?php
ini_set('display_errors', 'On');
require('lib/common.php');

if (!$isDebug) {
	accessDenied();
}

$twig = twigloader();

echo $twig->render('cheatsheet.twig', [
]);
