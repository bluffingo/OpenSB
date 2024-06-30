<?php
namespace OpenSB;

global $twig, $orange, $isDebug;

use SquareBracket\UnorganizedFunctions;

if ($orange->getLocalOptions()["skin"] == "biscuit") {
    echo $twig->render('design_test.twig',
        [
            "color_types" => [
                "primary", "secondary", "success", "danger"
            ]
        ]
    );
} else {
    UnorganizedFunctions::Notification("This page is not available under current conditions.", "/");
}