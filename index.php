<?php
require('lib/common.php');

$testVariable = (isset($_GET['test']) ? $_GET['test'] : null);

$videodatatest = [
	'id' => "aBcDeF",
	'title' => "Example Video",
	'description' => "This is an example video to test out the video box design. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur consectetur quam nec maximus laoreet. Mauris augue tellus, viverra id pulvinar id, ultricies quis felis. Suspendisse mollis nunc nec magna tincidunt lacinia.",
	'author' => "Epic Channel",
	'views' => 342213,
	'date' => "15 minutes ago"
];

$twig = twigloader();

echo $twig->render('index.twig', [
	'video_data' => $videodatatest
]);
