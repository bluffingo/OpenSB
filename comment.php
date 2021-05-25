<?php
require('lib/common.php');

if (!isset($_POST['vidid'])) {
	die('Wait, why are you trying to insert garbage to my database?');
} else if (!isset($_POST['comment']) or $_POST['comment'] == '') {
	die(); //don't output anything if this sneaky bastard didn't put anything to the comment field
}

$comment = [
	'u_username' => $currentUser['username'],
	'comment' => $_POST['comment'],
	'date' => time()
];

query("INSERT INTO comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
	[$_POST['vidid'],$_POST['comment'],$currentUser['id'],time(),0]);

$twig = twigloader();
echo $twig->render('components/comment.twig', [
	'data' => $comment
]);