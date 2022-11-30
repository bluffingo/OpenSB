<?php

namespace openSB;

require_once dirname(__DIR__) . '/private/class/common.php';

$videoData = $sql->fetchArray($sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id ORDER BY v.time ASC"));

$gourcelog = fopen("gourcelog.txt", "w") or die("Can't open Gource log.");

foreach ($videoData as $video) {
	$title = str_replace('/', '∕', $video['title']);
	$gourceData = sprintf('%s|%s|A|%s/%s.%s', $video['time'], $video['u_name'], $video['u_name'], $title, $video['u_name']);
	print($gourceData . PHP_EOL);
	fwrite($gourcelog, $gourceData . PHP_EOL);
}