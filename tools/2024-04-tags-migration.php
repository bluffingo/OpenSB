<?php
namespace OpenSB;

global $orange;

define("SB_DYNAMIC_PATH", dirname(__DIR__) . '/dynamic');
define("SB_PRIVATE_PATH", dirname(__DIR__) . '/private');
define("SB_VENDOR_PATH", dirname(__DIR__) . '/vendor');
define("SB_GIT_PATH", dirname(__DIR__) . '/.git'); // ONLY FOR makeVersionString() IN SquareBracket CLASS.

require_once dirname(__DIR__) . '/private/class/common.php';

function removeLastComma(&$array) {
    if (count($array) > 0) {
        end($array);
        $lastKey = key($array);
        $lastValue = &$array[$lastKey];
        $lastValue = rtrim($lastValue, ',');
    }
}


$sql = $orange->getDatabase();

$submissions = $sql->fetchArray($sql->query("SELECT * FROM videos"));

$stuff = 1;

$uniqueTags = [];

foreach ($submissions as $submission) {
    if (isset($submission["tags"]) && $submission["tags"] !== null && ($submission["tags"] != '[""]')) {
        $stuff = $stuff + 1;
        echo($stuff . PHP_EOL);
        $tags = json_decode($submission["tags"]);
        if ($tags !== null) {
            removeLastComma($tags);
            foreach ($tags as $tag) {
                if (!in_array($tag, $uniqueTags)) {
                    $uniqueTags[] = $tag;
                }
            }
        }
    }
}

var_dump($uniqueTags);