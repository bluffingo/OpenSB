<?php
//this uploads and converts the video, should switch to a better solution!
require('lib/common.php');
use Intervention\Image\ImageManager;
use Streaming\FFMpeg;
use FFMpeg\Coordinate;
use FFMpeg\Media;
use FFMpeg\Filters;

$manager = new ImageManager();

$video_id = substr(base64_encode(md5(bin2hex(random_bytes(6)))), 0, 11); //you are never too sure how much randomness you need.
$new = '';
foreach(str_split($video_id) as $char){
	if (rand(0, 1) == 1) {
		$char = str_rot13($char);
	} else if (rand(0, 2) == 2) {
		$char = '_';
	} else if (rand(0, 3) == 3) {
		$char = mb_strtoupper($char);
	} else if (rand(0, 4) == 4) {
		$char = '-';
	}
	$new .= $char;
}

if (isset($_POST['upload']) and isset($currentUser['username'])) {
	$title = (isset($_POST['title']) ? $_POST['title'] : null);
	$description = (isset($_POST['desc']) ? $_POST['desc'] : null);

	// Prevent videos with duplicate metadata since they are probably accidentally uploaded.
	if (result("SELECT COUNT(*) FROM videos WHERE title = ? AND description = ?", [$title, $description])) {
		die("Your video is already uploading or has been uploaded.");
	}

	// Rate limit uploading to 30 minutes, both to prevent spam and to prevent double uploads.
	if (result("SELECT COUNT(*) FROM videos WHERE time > ? AND author = ?", [time() - 60*30, $currentUser['id']])) {
		die("Please wait 30 minutes before uploading again. If you've already uploaded a video, it is being processed.");
	}

	$name       = $_FILES['fileToUpload']['name'];
	$temp_name  = $_FILES['fileToUpload']['tmp_name'];  // gets video info and thumbnail info
	$ext  = pathinfo( $_FILES['fileToUpload']['name'], PATHINFO_EXTENSION );
	$target_file = 'videos/'.$new.'.'.$ext;
	if (move_uploaded_file($temp_name, $target_file)){
		$config = [
			'timeout'          => 3600, // The timeout for the underlying process
			'ffmpeg.threads'   => 12,   // The number of threads that FFmpeg should use
			'ffmpeg.binaries'  => ($ffmpegPath ? $ffmpegPath : 'ffmpeg'),
			'ffprobe.binaries' => ($ffprobePath ? $ffprobePath : 'ffprobe'),
		];
		try {
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

			query("INSERT INTO videos (video_id, title, description, author, time, tags, videofile, videolength) VALUES (?,?,?,?,?,?,?,?)",
				[$new,$title,$description,$currentUser['id'],time(),json_encode(explode(', ', $_POST['tags'])),'videos/'.$new.'.mpd',ceil($metadata->getFormat()->get('duration'))]);

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

			redirect('./watch.php?v='.$new);
		} catch (Exception $e) {
			echo '<p>Something went wrong!:'.htmlspecialchars($e->getMessage()).'on line:'.htmlspecialchars($e->getLine()).'</p> <p>stack trace:'.htmlspecialchars($e->getTraceAsString()).'</p>';
			foreach (glob("videos/$new*") as $filename) {
			   unlink($filename);
			}
		}
	}
}

$twig = twigloader();
echo $twig->render('upload.twig');
