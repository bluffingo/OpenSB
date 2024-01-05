<?php

namespace OpenSB;

global $orange;

use Orange\OrangeException;
use Orange\Templating;
use Orange\Pages\Index;

$index = new Index($orange);
$data = $index->getData();

$twig = new Templating($orange);

echo $twig->render('index.twig', [
    'data' => $data,
]);
