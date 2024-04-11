<?php

namespace SquareBracket;

class LocalStorage implements Storage
{
    public function __construct(SquareBracket $orange) {
        // implement this later, -grkb 4/7/2023
    }

    public function processVideo($new, $target_file): void
    {
        if (str_starts_with(php_uname(), "Windows")) {
            pclose(popen(sprintf('start /B  php %s "%s" "%s" "1" > %s', SB_PRIVATE_PATH . '\scripts\processingworker.php', $new, $target_file, SB_DYNAMIC_PATH . '/videos/' . $new . '.log'), "r"));
        } else {
            system(sprintf('php %s "%s" "%s" "1" > %s 2>&1 &', SB_PRIVATE_PATH . '/scripts/processingworker.php', $new, $target_file, SB_DYNAMIC_PATH . '/videos/' . $new . '.log'));
        }
    }

    public function getVideoThumbnail($id): string
    {
        global $branding;

        if (file_exists('../dynamic/thumbnails/' . $id . '.png')) {
            return '../dynamic/thumbnails/' . $id . '.png';
        } else {
            return $branding["assets_location"] . '/placeholder.png';
        }
    }

    public function getImageThumbnail($id): string
    {
        global $branding;

        if  (file_exists('../dynamic/art_thumbnails/' . $id . '.jpg')) {
            return '../dynamic/art_thumbnails/' . $id . '.jpg';
        } else {
            return $branding["assets_location"] . '/placeholder.png';
        }
    }

    public function fileExists($file): bool
    {
        return file_exists($file);
    }

    public function processImage($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/art/' . $new . '.png';
        $target_thumbnail = SB_DYNAMIC_PATH . '/art_thumbnails/' . $new . '.jpg';

        UnorganizedFunctions::processImageSubmissionFile($temp_name, $target_file);
        UnorganizedFunctions::processImageSubmissionThumbnail($temp_name, $target_thumbnail);

        unlink($temp_name);
    }

    public function uploadProfilePicture($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/pfp/' . $new . '.png';

        UnorganizedFunctions::processProfilePicture($temp_name, $target_file);

        unlink($temp_name);
    }

    public function uploadCustomThumbnail($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/custom_thumbnails/' . $new . '.png';

        UnorganizedFunctions::processCustomThumbnail($temp_name, $target_file);

        unlink($temp_name);
    }
}