<?php

namespace openSB;

global $betty, $bettyTemplate;

use \Betty\BettyException;
use \Betty\Templating;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/betty/class/pages/Index.php';

try {
    $index = new \Betty\Pages\Index($betty);
    $data = $index->getIndexData();
} catch (BettyException $e) {
    error($e->getCode(), $e->getMessage());
}

// moved total subscribers to layout.php
if ($log) {
    $query = implode(', ', array_column($sql->fetchArray($sql->query("SELECT user FROM subscriptions WHERE id = ?", [$userdata['id']])), 'user'));
    if ($query != null) {
        $subscriptionVideos = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.author IN($query) ORDER BY v.id DESC LIMIT 16");
    } else {
        $subscriptionVideos = null;
    }
} else {
    $subscriptionVideos = null;
}
$twig = new \Betty\Templating($betty, $bettyTemplate);

echo $twig->render('index.twig', [
    'videos' => $data,
    'subscriptionVideos' => $subscriptionVideos,
    'updated' => (isset($_GET['updated']) ? true : false),
]);
