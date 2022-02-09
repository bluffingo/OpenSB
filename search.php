<?php
require('lib/common.php');

$query = isset($_GET['query']) ? $_GET['query'] : null;

// currently selects all uploaded videos
$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, v.author, v.tags FROM videos v JOIN users u ON v.author = u.id WHERE v.tags LIKE CONCAT('%', ?, '%') OR v.title LIKE CONCAT('%', ?, '%') OR v.description LIKE CONCAT('%', ?, '%') ORDER BY v.id DESC", [$query, $query, $query]);

$twig = twigloader();

echo $twig->render('search.twig', [
	'videos' => $videoData,
	'query' => $query
]);
