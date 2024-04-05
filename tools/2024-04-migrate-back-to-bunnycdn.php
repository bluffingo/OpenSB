<?php
namespace OpenSB;

global $orange, $storage, $isBluffingoSB;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

$sql = $orange->getDatabase();

// this only does videos. everything else should be migrated manually using the bunnycdn admin panel.
$submissions = $sql->fetchArray($sql->query("SELECT * FROM videos WHERE post_type = 0"));

if (!$isBluffingoSB) { die("The isBluffingoSB variable MUST be enabled."); }

// if this fails during a video, edit default_socket_timeout in php.ini to a higher value.
foreach ($submissions as $submission) {
    $fucking_takedown_bullshit = $sql->fetch("SELECT * FROM takedowns t WHERE t.submission = ?", [$submission["video_id"]]);
    if (!$fucking_takedown_bullshit) {
        if (str_contains($submission["videofile"], '/dynamic/videos/')) {
            echo "Migrating " . $submission["title"] . PHP_EOL;
            // this function already handles bunnycdn guids and things like that.
            $storage->processVideo($submission["video_id"], SB_DYNAMIC_PATH . '/videos/' . $submission["video_id"] . '.converted.mp4');
        }
    }
}