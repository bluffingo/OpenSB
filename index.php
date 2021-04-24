<?php
require('lib/common.php');

$testVariable = (isset($_GET['test']) ? $_GET['test'] : null);

$twig = twigloader();

echo $twig->render('index.twig', [
	'test' => $testVariable
]);
