<?php
namespace OpenSB;

global $database;
define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$videos = SB_DYNAMIC_PATH . '/videos';

$files = scandir($videos);

foreach ($files as $file) {
	$full_path = $videos . '/' . $file;
	$bunny_guid = substr($file, 34, -18);
	
	$id = $database->result("SELECT v.video_id FROM videos v WHERE v.videofile = ?", [$bunny_guid]);
	
	$new_full_path = $videos . '/' . $id . '.converted.mp4';
	
	print($full_path . ": " . $id . PHP_EOL);
	rename($full_path, $new_full_path);
}