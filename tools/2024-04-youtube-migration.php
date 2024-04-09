<?php
namespace OpenSB;

global $orange;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

// this is completely unsupported and is only intended to migrate my old content over to squarebracket, in the chance
// it gets multiple false strikes resulting in termination. please do not use this script to copy over channels with
// copyrighted content that you do not own.

// this script expects you've downloaded a youtube dump from yt-dlp like this:

// yt-dlp https://www.youtube.com/channel/[youtube_id] -o "%(id)s.%(ext)s" -f mp4 \
// --write-info-json --write-thumbnail --convert-thumbnails png

$gamerappaDump = "D:\youtube\gamerappa"; // contains a dump of @Gamerappa
$grkbDump = "D:\youtube\grkb"; // contains a dump of @GRKB (2015-2023 chaziz channel)
$chazpeloDump = "D:\youtube\chazpelo"; // contains a dump of @ChazpeloBlockers (2014-2015 chaziz channel)

$mergedEntries = [];

function addEntry(&$mergedEntries, $youtubeId, $filename, $directory) {
    if (strlen($youtubeId) != 11) {
        return; // youtube ids only have 11 characters
    }
    if (!isset($mergedEntries[$youtubeId])) {
        $mergedEntries[$youtubeId] = [];
    }
    $mergedEntries[$youtubeId][] = [
        $directory . '/' .  $filename,
    ];
}

$gamerappaStuff = scandir($gamerappaDump);
foreach ($gamerappaStuff as $filename) {
    $youtubeId = substr($filename, 0, strpos($filename, '.'));
    addEntry($mergedEntries, $youtubeId, $filename, $gamerappaDump);
}

$grkbStuff = scandir($grkbDump);
foreach ($grkbStuff as $filename) {
    $youtubeId = substr($filename, 0, strpos($filename, '.'));
    addEntry($mergedEntries, $youtubeId, $filename, $grkbDump);
}

$chazpeloStuff = scandir($chazpeloDump);
foreach ($chazpeloStuff as $filename) {
    $youtubeId = substr($filename, 0, strpos($filename, '.'));
    addEntry($mergedEntries, $youtubeId, $filename, $chazpeloDump);
}

// anything bigger than this is 99% likely to be a livestream. most of my videos don't exceed more than a few minutes.
// this may cause "false positives" where a few livestreams may get migrated over to squarebracket.
$maxFileSize = 200 * 1024 * 1024;

foreach ($mergedEntries as $youtubeId => $entry) {
    $infoJsonFilePath = $entry[1][0];
    $videoFilePath = $entry[2][0];

    $filesize = filesize($videoFilePath);

    if ($filesize > $maxFileSize) {
        echo "Skipping video $youtubeId - File size exceeds 200MB\n";
        continue;
    }

    $infoJsonData = file_get_contents($infoJsonFilePath);

    $infoData = json_decode($infoJsonData, true);

    $title = $infoData['title'];
    $description = $infoData['description'];
    $author = 1; // 1 on sb prod db is bluffingo
    $tags = implode(', ', $infoData['tags'] ?? []);
    $videofile = 'dynamic/videos/' . $youtubeId . '.mp4'; //wip
    $uploadDate = strtotime($infoData['upload_date']);

    $sql->query("
INSERT INTO videos (video_id, title, description, author, time, tags, videofile, flags, rating, original_site, original_time) VALUES (?,?,?,?,?,?,?,?,?,?,?)",
        [$youtubeId, $title, $description, $author, time(), $tags, "VeryFunnyPlaceholderAtm", 0, 'general', 'YouTube', $uploadDate]);

    echo "Inserted video with YouTube ID: $youtubeId\n";

    $destinationFilePath = SB_DYNAMIC_PATH . '/videos_test/' . $youtubeId . '.mp4';

    if (copy($videoFilePath, $destinationFilePath)) {
        echo "Copied video $youtubeId\n";
    } else {
        echo "Failed to copy video $youtubeId\n";
    }
}

// SELF-NOTES:
// this script should automatically remove these strings
// -~--~-~~-~- Please watch: "Its Gamerappa Bro " https://www.youtube.com/watch?v=3SoYlf99KEU -~--~-~~-~-
// 💬 Join the Gamerappium Discord Server! https://discord.gg/FAEEuMhn3M