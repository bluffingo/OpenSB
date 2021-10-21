<?php
chdir('../../');
$rawOutputRequired = true;
require(getcwd() . '/lib/common.php');

header('Content-Type: application/json');

$limit = (isset($_GET['limit']) ? $_GET['limit'] : 10);
$offset = (isset($_GET['offset']) ? $_GET['offset'] : 0);

$videoDataCount = result("SELECT COUNT(1) FROM videos v JOIN users u ON v.author = u.id");
$videoData = query("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT ? OFFSET ?", [$limit, $offset]);

// TODO: comments? likes? sex?
$apiOutput = [];
foreach ($videoData as $video) {
	$apiOutput[] = 
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
			'username' => $video['u_name']
		]
	];
}

echo json_encode(array('videos' => $apiOutput, 'count' => $videoDataCount));
