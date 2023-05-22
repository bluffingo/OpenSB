<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$query = $_GET['tags'] ?? null;

// currently selects all uploaded videos
$videoData = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.tags LIKE CONCAT('%', ?, '%') OR v.title LIKE CONCAT('%', ?, '%') OR v.description LIKE CONCAT('%', ?, '%') ORDER BY v.id DESC", [$query, $query, $query]);

$twig = twigloader();

echo $twig->render('search.twig', [
    'videos' => $videoData,
    'query' => $query
]);
