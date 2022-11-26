<?php

namespace openSB;

require dirname(__DIR__) . '/private/class/common.php';

$newsdata = $sql->query("SELECT * FROM news ORDER BY id DESC");

$twig = twigloader();
echo $twig->render('whats_new.twig', [
    'news' => $newsdata,
]);