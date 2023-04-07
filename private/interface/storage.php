<?php

namespace openSB;

interface Storage
{
    public function __construct();
    public function processVideo($new, $target_file);
    public function getVideoThumbnail($id);
    public function fileExists($file);
    public function uploadImage($temp_name, $target_file, $format, $resize = false, $width = 0, $height = 0);
}