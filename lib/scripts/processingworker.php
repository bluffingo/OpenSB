#!/usr/bin/php
<?php
namespace squareBracket;
include('lib/common.php');

use Intervention\Image\ImageManager;
use Streaming\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Coordinate;

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
	$duration = $ffprobe
           ->streams($target_file)
           ->videos()                   
           ->first()                  
           ->get('duration');
	$video = $ffmpeg->open($target_file);
	$dash = $video->dash()
		->setAdaption('id=0,streams=v id=1,streams=a') // Set the adaption.
		->x264() // Format of the video. vp9 would have been a better idea if it weren't for slow-ass speeds and no way of changing speeds.
		->autoGenerateRepresentations() // Auto generate representations
		->save(); // It can be passed a path to the method or it can be null
	$metadata = $dash->metadata();
	if (floor($duration) < 10) {
		if (floor($duration) == 0) {
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
	$img->save('assets/thumb/' . $new . '.png');
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
