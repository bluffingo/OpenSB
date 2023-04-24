<?php

namespace openSB\APIv2;
chdir('../../');
$rawOutputRequired = true;
require_once dirname(__DIR__) . '/../../private/class/common.php';

header('Content-Type: application/json');

$stats = $sql->fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount, (SELECT COUNT(*) FROM views) viewcount, (SELECT COUNT(*) FROM comments) commentcount");

$apiOutput = [
    'instance' => [
        'build_number' => $versionNumber,
        'git_commit' => \openSB\gitCommit(),
    ],
    'numbers' => [
        'total_users' => $stats['usercount'],
        'total_submissions' => $stats['videocount'],
        'total_views' => $stats['viewcount'],
        'total_comments' => $stats['commentcount'],
    ],
];

echo json_encode($apiOutput);
