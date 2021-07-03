<?php
chdir('../../');
$rawOutputRequired = true;
require('lib/common.php');

header('Content-Type: application/json');

$start = (isset($_GET['start']) ? $_GET['start'] : 0);
$limit = (isset($_GET['limit']) ? $_GET['limit'] : 10);

$videoData = query("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT ?, ?", [$start, $limit]);

// TODO: comments? likes?
$apiOutput = [];
foreach ($videoData as $video) {
	array_push($apiOutput, 
	[
		'id'	=> $video['video_id'],
		'title'	=> $video['title'],
		'description' => $video['description'],
		'time' => $video['time'],
		'views' => $video['views'],
		'videofile' => $video['videofile'],
		'videolength' => $video['videolength'],
		'flags' => [ // supposed to be a "videoflags" object
			'processing' => $video['flags'] & 0x2,
			'featured' => $video['flags'] & 0x2,
		],
		'tags' => $video['tags'],
		'author' => [ // supposed to be an "user" object
			'username' => $video['u_username']
		]
	]);
}

echo json_encode($apiOutput);
