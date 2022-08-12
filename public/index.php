<?php

namespace squareBracket;
ini_set('display_errors', 'On');
require dirname(__DIR__) . '/private/class/common.php';

$nonFunctionalShit = true;
$pageVariable = "index";

// currently selects all uploaded videos, should turn it into all featured only
$videoData = $sql->query("SELECT $userfields $videofields, v.category_id FROM videos v JOIN users u ON v.author = u.id WHERE `post_type` = 0 OR `post_type` = 1 ORDER BY RAND() LIMIT 12");
$videoDataRight = $sql->query("SELECT $userfields $videofields, v.category_id FROM videos v JOIN users u ON v.author = u.id WHERE `post_type` = 0 OR `post_type` = 1 ORDER BY v.time DESC LIMIT 12");
$artData = $sql->query("SELECT $userfields $videofields, v.category_id FROM videos v JOIN users u ON v.author = u.id WHERE `post_type` = 2 ORDER BY RAND() LIMIT 12");
// moved total subscribers to layout.php
if ($log) {
    $query = implode(', ', array_column($sql->fetchArray($sql->query("SELECT user FROM subscriptions WHERE id = ?", [$userdata['id']])), 'user'));
    if ($query != null) {
        $subscriptionVideos = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.author IN($query) ORDER BY v.id DESC LIMIT 4");
    } else {
        $subscriptionVideos = null;
    }
    $totalViews = $sql->result("SELECT SUM(views) FROM videos WHERE author = ?", [$userdata['id']]);
    $creationDate = $sql->result("SELECT joined FROM users WHERE id = ?", [$userdata['id']]);
} else {
    $subscriptionVideos = null;
    $totalViews = 0;
    $creationDate = 0;
}
$twig = twigloader();

echo $twig->render('index.twig', [
    'videos' => $videoData,
    'videos_right' => $videoDataRight,
    'artworks' => $artData,
    'subscriptionVideos' => $subscriptionVideos,
    'totalViews' => $totalViews,
    'creationDate' => $creationDate,
    'updated' => (isset($_GET['updated']) ? true : false),
]);
