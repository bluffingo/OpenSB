<?php
require('lib/common.php');
$id = (isset($_GET['i']) ? $_GET['i'] : null);

$lookData = fetch("SELECT $userfields i.* FROM image i JOIN users u ON i.author = u.id WHERE i.image_id = ?", [$id]);

if (!$lookData) error('404', __("The image/artwork you were looking for cannot be found."));

$twig = twigloader();
echo $twig->render('view.twig', [
	'image' => $lookData,
]);