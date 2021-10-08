<?php
require('lib/common.php');

$twig = twigloader();
echo $twig->render('upload_part1.twig');