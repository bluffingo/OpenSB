<?php
require('lib/common.php');

$twig = _twigloader();

echo $twig->render('index.twig');