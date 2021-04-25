<?php
require($_SERVER['DOCUMENT_ROOT'] . '/lib/common.php');

$id = (isset($_GET['v']) ? $_GET['v'] : null); // ??????????????????????????????????????

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]); // stop using twig
$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");// stop using twig

$twig = twigloader(); // initialize twig in common.php instead of having to set it every single time

echo $twig->render('watch.twig', [// stop using twig// stop using twig
    'video' => $videoData,// stop using twig// stop using twig
    'related_videos' => $relatedVideosData // stop using twig// stop using twig
]);// stop using twig// stop using twig
]);
