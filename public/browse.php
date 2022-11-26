<?php

namespace openSB;

require dirname(__DIR__) . '/private/class/common.php';

$type = ($_GET['type'] ?? 'all');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);

$where = ($type != 'all' ? "WHERE v.category_id = " . Videos::type_to_cat($type) : '');
$limit = sprintf("LIMIT %s,%s", (($page - 1) * $paginationLimit), $paginationLimit);
$videoData = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id $where ORDER BY v.time DESC $limit");
$count = $sql->result("SELECT COUNT(*) FROM videos v $where");

$twig = twigloader();
echo $twig->render('browse.twig', [
    'type' => $type,
    'levels' => $sql->fetchArray($videoData),
    'page' => $page,
    'level_count' => $count
]);