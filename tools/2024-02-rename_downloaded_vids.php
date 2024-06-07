<?php
namespace OpenSB;

global $orange;
define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

$videos = SB_DYNAMIC_PATH . '/videos';

$files = scandir($videos);

foreach ($files as $file) {
	$full_path = $videos . '/' . $file;
	$bunny_guid = substr($file, 34, -18);
	
	$id = $sql->result("SELECT v.video_id FROM videos v WHERE v.videofile = ?", [$bunny_guid]);
	
	$new_full_path = $videos . '/' . $id . '.converted.mp4';
	
	print($full_path . ": " . $id . PHP_EOL);
	rename($full_path, $new_full_path);
}