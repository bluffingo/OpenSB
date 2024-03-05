<?php
namespace OpenSB;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

$videoData = $sql->fetchArray($sql->query("SELECT v.* FROM videos v WHERE v.post_type = 0 ORDER BY v.time DESC"));

foreach ($videoData as $video) {
	$sql->query("UPDATE videos SET videofile = ? WHERE video_id = ?", ["/dynamic/videos/" . $video["video_id"], $video["video_id"]]);
}