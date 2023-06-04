<?php

namespace openSB\FinaliumApi;

global $auth;
chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';
header('Content-Type: application/json');

$post_data = json_decode(file_get_contents('php://input'), true);

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "User is banned!!!"
    ];
}

if (isset($post_data['submission'])) {
    if (isset($post_data['action'])) {
        $apiOutput = match ($post_data['action']) {
            'favorite' => [
                "favorited" => true,
                "number" => rand(0, 47101), // placeholder code
            ],
            default => [
                "error" => "Invalid interaction type, or NYI"
            ],
        };
    }
}

echo json_encode($apiOutput);
