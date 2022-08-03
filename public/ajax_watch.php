<?php

namespace squareBracket;

require('lib/common.php');
if (isset($_POST['limit'])) {
    $limit = (isset($_POST['limit']) ? $_POST['limit'] : 6);
    $offset = (isset($_POST['from']) ? $_POST['from'] : 0);
    $user = (isset($_POST['user']) ? $_POST['user'] : 0);

    $videoData = query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT ? OFFSET ?", [$limit, $offset]);

    $twig = twigloader();
    echo $twig->render('components/videolist.twig', [
        'videos' => $videoData,
    ]);
}
