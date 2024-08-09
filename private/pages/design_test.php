<?php
namespace OpenSB;

global $twig, $orange, $isDebug;

use SquareBracket\UnorganizedFunctions;

echo $twig->render('design_test.twig',
    [
        "color_types" => [
            "primary", "secondary", "success", "danger"
        ]
    ]
);