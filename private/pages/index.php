<?php

namespace OpenSB;

global $orange;

use SquareBracket\Pages\Index;
use SquareBracket\Templating;

$index = new Index($orange);
$data = $index->getData();

$twig = new Templating($orange);

echo $twig->render('index.twig', [
    'data' => $data,
]);
