<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

$twig = twigloader();

echo $twig->render('user.twig');
