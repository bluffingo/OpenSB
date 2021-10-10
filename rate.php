<?php
$rawOutputRequired = true;
require('lib/common.php');

if (!isset($_POST['vidid'])) {
	die(__("No POST data."));
} else if (!isset($_POST['rating']) or $_POST['rating'] == '') {
	die(); //don't output anything if there is no data.
}
if (result("SELECT COUNT(rating) FROM rating WHERE video=? AND user=?", [result("SELECT id FROM videos WHERE video_id=?", [$_POST['vidid']]),$userdata['id']]) != 0) {
	query("DELETE FROM rating WHERE user=? AND video=?",
		[$userdata['id'],result("SELECT id FROM videos WHERE video_id=?",[$_POST['vidid']])]);
	echo 0;
} else {
	query("INSERT INTO rating (user, video, rating) VALUES (?,?,?)",
		[$userdata['id'],result("SELECT id FROM videos WHERE video_id=?",[$_POST['vidid']]),$_POST['rating']]);
	echo 1;
}
