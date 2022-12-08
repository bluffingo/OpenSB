<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
$limit = sprintf("LIMIT %s,%s", (($page - 1) * $paginationLimit), $paginationLimit);
$artData = $sql->query("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE `post_type` = 2 ORDER BY v.time DESC $limit");

$twig = twigloader();
echo $twig->render('artwork.twig', [
    'artData' => $artData,
]);