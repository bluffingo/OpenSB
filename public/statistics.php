<?php

namespace openSB;
require_once dirname(__DIR__) . '/private/class/common.php';
use SpfPhp\SpfPhp;

$stats = $sql->fetch("SELECT (SELECT COUNT(*) FROM users) usercount, (SELECT COUNT(*) FROM videos) videocount, (SELECT COUNT(*) FROM views) viewcount, (SELECT COUNT(*) FROM comments) commentcount");

$vidGraph = GraphData::getVideoGraph();
$userGraph = GraphData::getUserGraph();
$commentGraph = GraphData::getCommentGraph();

SpfPhp::beginCapture();
$twig = twigloader();
echo $twig->render('stats.twig', [
    'stats' => $stats,
    'video_graph_data' => $vidGraph,
    'user_graph_data' => $userGraph,
    'comment_graph_data' => $commentGraph,
]);
SpfPhp::autoRender();