#!/usr/bin/php
<?php
include('lib/common.php');

use Intervention\Image\ImageManager;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\x264;
use FFMpeg\FFProbe;
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
	$ffprobe = FFProbe::create($config);
	$video = $ffmpeg->open($target_file);
	//$duration = $ffprobe
	//	->format($video)    // extracts file informations
	//	->get('duration');  // returns the duration property
	//if (floor($duration) < 10) {
	//	if (floor($duration) == 0) {
	//		$video->frame(Coordinate\TimeCode::fromSeconds(floor($metadata->getFormat()->get('duration'))))
	//			->save('assets/thumb/' . $new . '.png');
	//	} else {
	//		$video->frame(Coordinate\TimeCode::fromSeconds(floor($metadata->getFormat()->get('duration')) - 1))
	//			->save('assets/thumb/' . $new . '.png');
	//	}
	//} else {
		$video->frame(Coordinate\TimeCode::fromSeconds(1))
			->save('assets/thumb/' . $new . '.png');
	//}
	$img = $manager->make('assets/thumb/' . $new . '.png');
	$img->resize(640, 360);
	$img->save('assets/thumb/' . $new . '.png');

	$video->save(new x264(), 'videos/' . $new . '.converted.mp4');
	$video->save(new webm(), 'videos/' . $new . '.webm');
	unlink($target_file);

	$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$new]);

	query("UPDATE videos SET videolength = ?, flags = ? WHERE video_id = ?",
		[ceil($duration), $videoData['flags'] ^ 0x2, $new]);
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