<?php
require('lib/common.php');

$twig = twigloader();
echo $twig->render('partnership.twig');