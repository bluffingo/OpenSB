<?php
require('lib/common.php');

$postdatatest = [
	'id' => "1",
	'category' => "General",
	'title' => "Example Thread",
	'description' => "Holy shit are people uninterested.",
	'author' => "Katt Wallace",
	'views' => 342213,
	'date' => "15 minutes ago"
];

$twig = twigloader();
echo $twig->render('topic.twig', [
	'post_data' => $postdatatest
]);