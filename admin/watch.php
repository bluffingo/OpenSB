<?php
require('lib/common.php');

$id = (isset($_GET['v']) ? $_GET['v'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
$relatedVideosData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC");

$twig = _twigloader();

echo $twig->render('watch.twig', [
    'video' => $videoData,
    'related_videos' => $relatedVideosData 
]);