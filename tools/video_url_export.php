<?php
namespace OpenSB;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN Orange CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

// 1669421577 is November 2022, during the "transitional" period between squareBracket.pw and Qobo.tv.
// Anything uploaded after that date only exists inside of the BunnyCDN shit Qobo was using.
// Anything before then has higher quality versions from squareBracket.pw.
$videoData = $sql->fetchArray($sql->query("SELECT v.* FROM videos v WHERE v.post_type = 0 AND v.time > 1669421577 ORDER BY v.time DESC"));

$file = fopen("video_urls.txt", "w") or die("Can't open.");

// note to anyone: this bunnycdn video cdn domain no longer works.
foreach ($videoData as $video) {
	$name = "https://vz-05de22db-96d.b-cdn.net/" . $video["videofile"] . "/playlist.m3u8";
	print($name . PHP_EOL);
	fwrite($file, $name . PHP_EOL);
}