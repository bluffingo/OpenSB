<?php
require('lib/common.php');

// This isn't ready for production.
if (!$isDebug) {
	accessDenied();
}

$id = (isset($_GET['thread']) ? $_GET['thread'] : null);

// doesn't fuckin' work, is the only solution just about using some shitty retarded forum hosting service from usa?
// fuck off. not dealing with slow-ass outdated software from fucking 2006.
// no icty, this wasnt based on fucking finobe nor blockland. POKTUBE HAD FORUMS. SBNEXT IS NOT AN EXCUSE.
$postData = query("SELECT * FROM posts WHERE thread_id = ?", [$id]);

//no one is interested so don't expect this to be done until about beta 2
//-gr 7/30/2021

$twig = twigloader();
echo $twig->render('topic.twig', [
	'post_data' => $postData
]);
