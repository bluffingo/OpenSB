<?php
ini_set('display_errors', 'On');
require('lib/common.php');

$twig = twigloader();

echo $twig->render('about.twig', [
]);
