<?php
chdir('../../');
$rawOutputRequired = true;
require('lib/common.php');

header('Content-Type: application/json');

$id = (isset($_GET['id']) ? $_GET['id'] : null);

$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);

if (!$videoData) {
	$apiOutput = [ 'error' => "No video specified or invalid video ID", 'code' => "52e44101" ];

	echo json_encode($apiOutput);
	die();
}

// TODO: comments? likes?
$apiOutput = [
	'id'	=> $videoData['video_id'],
	'title'	=> $videoData['title'],
	'description' => $videoData['description'],
	'time' => $videoData['time'],
	'views' => $videoData['views'],
	'videofile' => $videoData['videofile'],
	'videolength' => $videoData['videolength'],
	'flags' => [ // supposed to be a "videoflags" object
		'processing' => $videoData['flags'] & 0x2,
		'featured' => $videoData['flags'] & 0x2,
	],
	'tags' => $videoData['tags'],
	'author' => [ // supposed to be an "user" object
		'username' => $videoData['u_name']
	]
];

echo json_encode($apiOutput);
