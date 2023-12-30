<?php

namespace Orange;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Orange\Storage;

class LocalStorage implements Storage
{
    public function __construct(Orange $orange) {
        // implement this later, -grkb 4/7/2023
    }

    public function processVideo($new, $target_file) {
        if (str_starts_with(php_uname(), "Windows")) {
            pclose(popen(sprintf('start /B  php %s "%s" "%s" > %s', dirname(__DIR__) . '\scripts\processingworker.php', $new, $target_file, dirname(__DIR__) . '/../dynamic/videos/' . $new . '.log'), "r"));
        } else {
            system(sprintf('php %s "%s" "%s" > %s 2>&1 &', dirname(__DIR__) . '/scripts/processingworker.php', $new, $target_file, dirname(__DIR__) . '/../dynamic/videos/' . $new . '.log'));
        }
    }

    public function getVideoThumbnail($id) {
        if  (file_exists('../dynamic/thumbnails/' . $id . '.png')) {
            return '../dynamic/thumbnails/' . $id . '.png';
        }
    }

    public function getImageThumbnail($id) {
        if  (file_exists('../dynamic/art_thumbnails/' . $id . '.jpg')) {
            return '../dynamic/art_thumbnails/' . $id . '.jpg';
        }
    }

    public function fileExists($file) {
        return file_exists($file);
    }

    public function uploadImage($temp_name, $target_file, $format, $resize = false, $width = 0, $height = 0) {
        $manager = new ImageManager();
        if (move_uploaded_file($temp_name, $target_file)) {
            $img = $manager->make($target_file);
            if ($resize) {
                $img->resize($width, $height);
            } else {
                $img->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            $img->save($target_file, 97, $format);
        }
    }

    public function processImage($temp_name, $new) {
        $manager = new ImageManager(Driver::class);
        $target_file = dirname(__DIR__) . '/../dynamic/art/' . $new . '.png';
        $target_thumbnail = dirname(__DIR__) . '/../dynamic/art_thumbnails/' . $new . '.jpg';

        Utilities::processImageSubmissionFile($temp_name, $new, $target_file);
        Utilities::processImageSubmissionThumbnail($temp_name, $new, $target_thumbnail);

        unlink($temp_name);
    }
}