<?php
namespace OpenSB;

global $orange;
define("SB_ROOT_PATH", dirname(__DIR__));
define("SB_DYNAMIC_PATH", SB_ROOT_PATH . '/dynamic');
define("SB_PUBLIC_PATH", SB_ROOT_PATH . '/public'); // we need this for SquareBracketTwigExtension
define("SB_PRIVATE_PATH", SB_ROOT_PATH . '/private');
define("SB_VENDOR_PATH", SB_ROOT_PATH . '/vendor');
define("SB_GIT_PATH", SB_ROOT_PATH . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

// 1669421577 is November 2022, during the "transitional" period between squareBracket.pw and Qobo.tv.
// Anything uploaded after that date only exists inside of the BunnyCDN shit Qobo was using.
// Anything before then has higher quality versions from squareBracket.pw.

// 1708565417 is March 2024 or something I DONT FUCKING KNOW FUCK YOU SKS2002 GENUINELY GO FUCK YOURSELF
// FUCK YOU AND YOUR STUPID BITTOCO PIECE OF SHIT ORGANIZATION -chaziz 11/5/2024
$videoData = $sql->fetchArray($sql->query("SELECT v.* FROM videos v WHERE v.post_type = 0 AND v.time < 1619537017 ORDER BY v.time DESC"));

//$videoData = $sql->fetchArray($sql->query("SELECT v.* FROM videos v WHERE v.post_type = 0 AND v.author = 105 AND v.time BETWEEN 1619537017 AND 1641013200 ORDER BY v.time DESC"));

$file = fopen("video_urls.txt", "w") or die("Can't open.");

// note to anyone: this bunnycdn video cdn domain no longer works.
foreach ($videoData as $video) {
	$name = "https://vz-1655021f-b55.b-cdn.net/" . $video["videofile"] . "/playlist.m3u8";
	print($name . PHP_EOL);
	fwrite($file, $name . PHP_EOL);
}