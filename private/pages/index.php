<?php

namespace OpenSB;

global $twig, $orange;

use SquareBracket\Pages\Index;
use SquareBracket\Templating;

$index = new Index($orange);
$data = $index->getData();

echo $twig->render('index.twig', [
    'data' => $data,
]);
