<?php

namespace openSB\APIv2;
chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';

header('Content-Type: application/json');

$id = (isset($_GET['id']) ? $_GET['id'] : null);

$submission = \openSB\Videos::getVideoData($userfields, $id);

if(!$submission) {
    $apiOutput = ['error' => "Submission unavailable"];

    echo json_encode($apiOutput);
    die();
}

// move this to getVideoData
$totalLikes = $sql->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$submission['id']]);
$totalDislikes = $sql->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$submission['id']]);

// TODO: comments? likes?
$apiOutput = [
    'id' => $submission['video_id'],
    'title' => $submission['title'],
    'description' => $submission['description'],
    'time' => $submission['time'],
    'views' => $submission['views'],
    'file' => $submission['videofile'],
    'tags' => $submission['tags'],
    'author' => [
        'id' => $submission['u_id'],
        'name' => $submission['u_name'],
        'color' => $submission['u_customcolor'],
    ],
    'ratings' => [
        'likes' => $totalLikes,
        'dislikes' => $totalDislikes,
    ],
];

echo json_encode($apiOutput);
