<?php
//this uploads and converts the video, should switch to a better solution!
require('lib/common.php');
use Spatie\Async\Pool;
use Intervention\Image\ImageManager;
use Streaming\FFMpeg;
use FFMpeg\Coordinate;
use FFMpeg\Media;
use FFMpeg\Filters;

$manager = new ImageManager();

//TODO: make video IDs not use multiple underscores.

$video_id = substr(base64_encode(md5(bin2hex(random_bytes(6)))), 0, 11); //you are never too sure how much randomness you need.
$new = '';
foreach(str_split($video_id) as $char){
	switch (rand(0, 4)) {
		case rand(0, 1):
			$char = str_rot13($char);
			break;
		case rand(0, 2):
			$char = '_';
			break;
		case rand(0, 3):
			$char = mb_strtoupper($char);
			break;
		case rand(0, 4):
			$char = '-';
			break;
	}
	$new .= $char;
}
ob_start();
$twig = twigloader();
echo $twig->render('upload.twig', [
	"uploaded" => isset($_POST['upload']), 
	"video_id" => $new ]
);
if (isset($_POST['upload'])) {
	sleep(5); //this is to fix videos not starting to convert.
}
$size = ob_get_length();
header("Content-Encoding: none");  
header("Content-Length: {$size}");
header("Connection: close");
ob_end_flush();
ob_flush();
flush();


if (isset($_POST['upload']) and isset($currentUser['username'])) {
	$title = (isset($_POST['title']) ? $_POST['title'] : null);
	$description = (isset($_POST['desc']) ? $_POST['desc'] : null);

	// Prevent videos with duplicate metadata since they are probably accidentally uploaded.
	if (result("SELECT COUNT(*) FROM videos WHERE title = ? AND description = ?", [$title, $description])) {
		die("Your video is already uploading or has been uploaded.");
	}

	// Rate limit uploading to 2 minutes, both to prevent spam and to prevent double uploads.
	// Shortened because 30 minutes is retarded.
	if (result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 60*2, $currentUser['id']])) {
		die("Please wait 2 minutes before uploading again. If you've already uploaded a video, it is being processed.");
	}

	$pool = Pool::create();
	$pool->add(function () use ($currentUser, $ffmpegPath, $ffprobePath, $manager, $new, $title, $description) {
		try {
			$name       = $_FILES['fileToUpload']['name'];
			$temp_name  = $_FILES['fileToUpload']['tmp_name'];  // gets video info and thumbnail info
			$ext  = pathinfo( $_FILES['fileToUpload']['name'], PATHINFO_EXTENSION );
			$target_file = 'videos/'.$new.'.'.$ext;
			if (move_uploaded_file($temp_name, $target_file)){
				query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags) VALUES (?,?,?,?,?,?,?,?)",
					[$new,$title,$description,$currentUser['id'],time(),json_encode(explode(', ', $_POST['tags'])),'videos/'.$new.'.mpd',"0010"]);
				$config = [
					'timeout'          => 3600, // The timeout for the underlying process
					'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
					'ffmpeg.binaries'  => ($ffmpegPath ? $ffmpegPath : 'ffmpeg'),
					'ffprobe.binaries' => ($ffprobePath ? $ffprobePath : 'ffprobe'),
				];
				$ffmpeg = FFMpeg::create($config);
				$video = $ffmpeg->open($target_file);
				$dash = $video->dash()
					->setAdaption('id=0,streams=v id=1,streams=a') // Set the adaption.
					->x264() // Format of the video. Alternatives: x264() and vp9()
					->autoGenerateRepresentations() // Auto generate representations
					->save(); // It can be passed a path to the method or it can be null
				$metadata = $dash->metadata();
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
				$img->save('assets/thumb/' . $new . '.png');
				unlink($target_file);
				query("UPDATE videos SET videolength = ?, flags = ? WHERE video_id = ?", [ceil($metadata->getFormat()->get('duration')), "0000", $new]);
			}
		} catch (Exception $e) {
			foreach (glob("videos/$new*") as $filename) {
			   unlink($filename);
			}
			query("UPDATE videos SET flags = ? WHERE video_id = ?", ["0020", $new]);
		}
	})->then(function ($output) use ($new, $title, $description, $currentUser, $webhook) {
		// Discord webhook stuff
		if ($webhook) {
			$webhookdata = [
				'video_id' => $new,
				'name' => $title,
				'description' => $description,
				'u_id' => $currentUser['id'],
				'u_name' => $currentUser['username']
			];

			newVideoHook($webhookdata);
		}
	});
}
