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

$submissions = $database->fetchArray($database->query("SELECT * FROM videos"));

$uniqueTags = [];

foreach ($submissions as $submission) {
    if (isset($submission["tags"]) && ($submission["tags"] != '[""]')) {
        $tags = json_decode($submission["tags"]);
        if ($tags !== null) {
            foreach ($tags as $tag) {
                if (!in_array($tag, $uniqueTags)) {
                    $uniqueTags[] = $tag;
                }
            }
        }
    }
}

foreach ($uniqueTags as $tag) {
    if (!$database->result("SELECT tag_id FROM tag_meta WHERE name = ?", [$tag])) {
        $database->query("INSERT INTO tag_meta (name, latestUse) VALUES (?,?)", [$tag, time()]);
    }
}

foreach ($submissions as $submission) {
    if (isset($submission["tags"]) && ($submission["tags"] != '[""]')) {
        $tags = json_decode($submission["tags"]);
        if ($tags !== null) {
            foreach ($tags as $tag) {
                $tagID = $database->fetchArray($database->query("SELECT tag_id FROM tag_meta WHERE name = ?", [$tag]))[0]['tag_id'];
                if (!$database->result("SELECT tag_id FROM tag_index WHERE tag_id = ? AND video_id = ?", [$tagID, $submission['id']])) {
                    $database->query("INSERT INTO tag_index (video_id, tag_id) VALUES (?,?)", [$submission['id'], $tagID]);
                }
            }
        }
    }
}