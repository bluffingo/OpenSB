<?php
require('lib/common.php');

// This isn't ready for production.
if (!$isDebug) {
	notReady();
}

$id = (isset($_GET['thread']) ? $_GET['thread'] : null);

$postData = fetch("SELECT $userfields v.* FROM posts v JOIN users u ON v.author = u.id WHERE v.id = ?", [$id]);

//no one is interested so don't expect this to be done until about beta 2
//-gr 7/30/2021

$postdatatest = [
	'id' => "1",
	'category' => "General",
	'title' => "Example Thread",
	'description' => "Holy shit are people uninterested.",
	'author' => 1,
	'views' => 342213,
	'date' => "15 minutes ago"
];

$twig = twigloader();
echo $twig->render('topic.twig', [
	'post_data' => $postData
]);
