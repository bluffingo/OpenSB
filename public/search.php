<?php

namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

$query = isset($_GET['tags']) ? $_GET['tags'] : null;

if ($query == "oneoneone") {
    setcookie('frontend', "layout111", 2147483647); // an easy way of letting people switch to 111
} elseif ($query == "fuckgoback") {
    setcookie('frontend', "sbnext", 2147483647); // an easy way of letting people switch back to finalium
}

// currently selects all uploaded videos
$videoData = query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.tags LIKE CONCAT('%', ?, '%') OR v.title LIKE CONCAT('%', ?, '%') OR v.description LIKE CONCAT('%', ?, '%') ORDER BY v.id DESC", [$query, $query, $query]);

$twig = twigloader();

echo $twig->render('search.twig', [
    'videos' => $videoData,
    'query' => $query
]);
