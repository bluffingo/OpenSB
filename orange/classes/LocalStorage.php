<?php

namespace Orange;

use Intervention\Image\ImageManager;

class LocalStorage implements Storage
{
    public function __construct() { 
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
        return file_exists('../dynamic/thumbnails/' . $id . '.png');
    }

    public function getImageThumbnail($id) {
        return file_exists('../dynamic/art_thumbnails/' . $id . '.jpeg');
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
            }
            $img->save($target_file, 0, $format);
        }
    }

    public function processImage($temp_name, $new) {
        $manager = new ImageManager();
        $target_file = dirname(__DIR__) . '/../dynamic/art/' . $new . '.png';
        $target_thumbnail = dirname(__DIR__) . '/../dynamic/art_thumbnails/' . $new . '.jpg';
        if (move_uploaded_file($temp_name, $target_file)) {
            $img = $manager->make($target_file);
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($target_file);
            $img = $manager->make($target_file)->encode('jpg', 80);
            $img->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $img->save($target_thumbnail);
        }
    }
}