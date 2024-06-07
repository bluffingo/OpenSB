<?php

namespace OpenSB;

global $twig;

// special thanks relating exclusively to the squarebracket instance unfortunately won't count.
// -chaziz 6/7/2024

$data = [
    "mpratt" => [
        "name" => "Michael Pratt",
        "url" => "https://github.com/mpratt",
        "reason" => "Creating the RelativeTime library."
    ]
];

echo $twig->render('special_thanks.twig', [
    'data' => $data,
]);