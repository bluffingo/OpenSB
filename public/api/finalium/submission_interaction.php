<?php

namespace openSB\FinaliumApi;

chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';
header('Content-Type: application/json');

$apiOutput = [
    "error" => "Invalid request."
];

if ($auth->getUserBanData()) {
    $apiOutput = [
        "error" => "User is banned!!!"
    ];
}

if (isset($_POST['submission'])) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'favorite':
                $apiOutput = [
                    "favorited" => true,
                    "number" => rand(0, 47101), // placeholder code
                ];
                break;
            default:
                $apiOutput = [
                    "error" => "Invalid interaction type, or NYI"
                ];
                break;
        }
    }
}

echo json_encode($apiOutput);
