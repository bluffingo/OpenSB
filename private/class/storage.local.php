<?php

namespace openSB;

class LocalStorage implements Storage
{
    public function __construct() { 
        // implement this later, -grkb 4/7/2023
    }

    public function processVideo($new, $target_file) {
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen(sprintf('start /B  php %s "%s" "%s" > %s', dirname(__DIR__) . '\scripts\processingworker.php', $new, $target_file, dirname(__DIR__) . '/../dynamic/videos/' . $new . '.log'), "r"));
        } else {
            system(sprintf('php %s "%s" "%s" > %s 2>&1 &', dirname(__DIR__) . '/scripts/processingworker.php', $new, $target_file, dirname(__DIR__) . '/../dynamic/videos/' . $new . '.log'));
        }
    }

    public function getVideoThumbnail($id) {
        return file_exists('../dynamic/thumbnails/' . $id . '.png');
    }
}