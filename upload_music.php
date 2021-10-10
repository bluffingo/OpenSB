<?php
//this uploads the music, should switch to a better solution!
require('lib/common.php');

//TODO: make music IDs not use multiple underscores.

$music_id = substr(base64_encode(md5(bin2hex(random_bytes(6)))), 0, 11); //you are never too sure how much randomness you need.
$new = '';
foreach(str_split($music_id) as $char){
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

if (isset($_POST['upload']) and isset($userdata['username'])) {
	$title = (isset($_POST['title']) ? $_POST['title'] : null);

	// Prevent videos with duplicate metadata since they are probably accidentally uploaded.
	if (result("SELECT COUNT(*) FROM music WHERE title = ?", [$title])) {
		die(__("Your music is already uploading or has been uploaded."));
	}

	// Rate limit uploading to 2 minutes, both to prevent spam and to prevent double uploads.
	if (result("SELECT COUNT(*) FROM music WHERE time > ? AND author = ?", [time() - 60*2, $userdata['id']])) {
		die(__("Please wait 2 minutes before uploading again. If you've already uploaded a music, it is being processed."));
	}

	$name       = $_FILES['fileToUpload']['name'];
	$temp_name  = $_FILES['fileToUpload']['tmp_name'];  // gets video info and thumbnail info
	$ext  = pathinfo( $_FILES['fileToUpload']['name'], PATHINFO_EXTENSION );
	$target_file = 'music/'.$new.'.'.$ext;
	if (move_uploaded_file($temp_name, $target_file)){
		query("INSERT INTO music (music_id, title, author, time, file) VALUES (?,?,?,?,?)",
			[$new,$title,$userdata['id'],time(),'music/'.$new.'.'.$ext]);

		redirect('./listen.php?m='.$new);
	}
}

$twig = twigloader();
echo $twig->render('upload_music.twig');
