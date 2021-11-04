<?php
require('lib/common.php');
$id = (isset($_GET['m']) ? $_GET['m'] : null);

$listenData = fetch("SELECT $userfields m.* FROM music m JOIN users u ON m.author = u.id WHERE m.music_id = ?", [$id]);

if (!$listenData) error('404', __("The music you were looking for cannot be found."));

query("UPDATE videos SET views = views + '1' WHERE video_id = ?", [$id]);

$twig = twigloader();
echo $twig->render('listen.twig', [
	'music' => $listenData,
]);