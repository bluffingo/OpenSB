<?php
//just do this fucking code later. -7/30/2021 gamerappa
//i lost all hope in humanity, i have no hope. fuck you chrischan -7/30/2021 gamerappa
require('lib/common.php');

// This isn't ready for production.
if (!$isDebug) {
	notReady();
}

if (isset($_POST['upload']) and isset($currentUser['username'])) {
	$title = (isset($_POST['title']) ? $_POST['title'] : null);
	$description = (isset($_POST['desc']) ? $_POST['desc'] : null);
	query("INSERT INTO posts (title, content, author, time) VALUES (?,?,?,?)",
			[$title,$description,$currentUser['id'],time()]);
}

$twig = twigloader();
echo $twig->render('post.twig');
