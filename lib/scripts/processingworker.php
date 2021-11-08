#!/usr/bin/php
<?php
include('lib/common.php');

use Intervention\Image\ImageManager;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Coordinate;
use FFMpeg\Media;
use FFMpeg\Filters;

$manager = new ImageManager();

$config = [
	'timeout'          => 3600, // The timeout for the underlying process
	'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
	'ffmpeg.binaries'  => ($ffmpegPath ? $ffmpegPath : 'ffmpeg'),
	'ffprobe.binaries' => ($ffprobePath ? $ffprobePath : 'ffprobe'),
];

$new = $argv[1];
$target_file = $argv[2];

try {
	$ffmpeg = FFMpeg::create($config);
	$video = $ffmpeg->open($target_file);
/*	$metadata = $video->metadata();
 	if (floor($metadata->getFormat()->get('duration')) < 10) {
		if (floor($metadata->getFormat()->get('duration')) == 0) {
			$video->frame(Coordinate\TimeCode::fromSeconds(floor($metadata->getFormat()->get('duration'))))
				->save('assets/thumb/' . $new . '.png');
		} else {
			$video->frame(Coordinate\TimeCode::fromSeconds(floor($metadata->getFormat()->get('duration')) - 1))
				->save('assets/thumb/' . $new . '.png');
		}
	} else {
		$video->frame(Coordinate\TimeCode::fromSeconds(10))
			->save('assets/thumb/' . $new . '.png');
	}
	$img = $manager->make('assets/thumb/' . $new . '.png');
	$img->resize(640, 360);
	$img->save('assets/thumb/' . $new . '.png'); */
	$video->save(new webm(), 'videos/' . $new . '.webm');
	unlink($target_file);

	$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$new]);

	query("UPDATE videos SET videolength = ?, flags = ? WHERE video_id = ?",
		['69', $videoData['flags'] ^ 0x2, $new]);
} catch (Exception $e) {
	echo "Something went wrong!:". $e->getMessage();
}

// Discord webhook stuff
if ($webhook) {
	$webhookdata = [
		'video_id' => $new,
		'name' => $videoData['title'],
		'description' => $videoData['description'],
		'u_id' => $videoData['u_id'],
		'u_name' => $videoData['u_username']
	];

	newVideoHook($webhookdata);
}
