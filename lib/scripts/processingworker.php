#!/usr/bin/php
<?php
//namespace pokTwo;
// I AM ABOUT TO GO FUCKING INSANE -grkb june 28th 2022
namespace squareBracket;

use FFMpeg\Coordinate;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Filters;
use FFMpeg\Format\Video\x264;

require('lib/common.php');
$config = [
    'timeout' => 3600, // The timeout for the underlying process
    'ffmpeg.threads' => 12,   // The number of threads that FFmpeg should use
    'ffmpeg.binaries' => ($ffmpegPath ? $ffmpegPath : 'ffmpeg'),
    'ffprobe.binaries' => ($ffprobePath ? $ffprobePath : 'ffprobe'),
];

$new = $argv[1];
$target_file = $argv[2];
try {
	$ffmpeg = FFMpeg::create($config);
	$ffprobe = FFProbe::create($config);
	$h264 = new X264();
	// $flv = new FLV();

	$h264->setAudioKiloBitrate(196)->setAdditionalParameters(array('-ar', '44100'));


	$video = $ffmpeg->open($target_file);
			
	//get frame count
	$duration = $ffprobe
		->streams($target_file)	// extracts file informations
		->videos()              // filters video streams
		->first()               // returns the first video stream			
		->get('nb_frames');  	// returns the duration property
			
	//get fractional framerate
	$fracFramerate = $ffprobe
		->streams($target_file)	// extracts file informations
		->videos()              // filters video streams
		->first()               // returns the first video stream	
		->get("avg_frame_rate");
				
	//get the actual framerate
	$framerate = explode("/", $fracFramerate)[0] / explode("/", $fracFramerate)[1];
            
			
	//this doesn't scale too well with short videos.
	$seccount = round($duration / 4);
	$seccount2 = $seccount * 1.5;
	$seccount3 = $seccount2 + $seccount - 1;

	$frame = $video->frame(Coordinate\TimeCode::fromSeconds($seccount2 / $framerate));
	$frame->filters()->custom('scale=512x288');
	$frame->save('assets/thumb/' . $new . '.png');

	$video->filters()->resize(new Coordinate\Dimension(1280, 720), Filters\Video\ResizeFilter::RESIZEMODE_INSET, true)
		->custom('format=yuv420p');
	$video->save($h264, 'videos/' . $new . '.converted.mp4');
	debug_print_backtrace();
	unlink($target_file);
	delete_directory($preload_folder);
	
	$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$new]);

	query("UPDATE videos SET videolength = ?, flags = ? WHERE video_id = ?",
		[round($duration / $framerate), $videoData['flags'] ^ 0x2, $new]);
} catch (Exception $e) {
	echo "(p2 uploader port) Something went wrong!:". $e->getMessage();
}

clearstatcache();

if (0 == filesize("videos/" . $new . ".converted.mp4")) {
    unlink("videos/" . $new . ".converted.mp4");
    delete_directory($preload_folder);
    $failcount++;
}

if ($failcount == 1) {
	unlink("videos/" . $new . ".converted.mp4");
	delete_directory($preload_folder);
	die("Fuck");
}