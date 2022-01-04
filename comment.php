<?php
require('lib/common.php');
if (isset($_POST['really']))
{
	switch ($_POST['type'])
    {
        case "video":
            $type = 0;
			$table = "comments";
			$id = $_POST['vidid'];
		break;
        case "profile":
            $type = 1;
			$table = "channel_comments";
			$id = $_POST['uid'];
		break;
	}
} else {
die(__("this is invalid"));
}

$comment = [
	'u_name' => $userdata['name'],
	'u_customcolor' => $userdata['customcolor'],
	'comment' => $_POST['comment'],
	'date' => time()
];

if ($type == 0) {
query("INSERT INTO comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
	[$id,$_POST['comment'],$userdata['id'],time(),0]);
} elseif ($type == 1) {
query("INSERT INTO channel_comments (id, comment, author, date, deleted) VALUES (?,?,?,?,?)",
[$id,$_POST['comment'],$userdata['id'],time(),0]);
} else {
die(__("this is still invalid"));
}

if ($frontend != "retro") {
$twig = twigloader();
echo $twig->render('components/comment.twig', [
	'data' => $comment
]);
}