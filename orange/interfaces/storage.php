<?php

namespace Orange;

interface Storage
{
    public function __construct(Orange $orange);
    // Process a video post.
    public function processVideo($new, $target_file);
    // Get video post's thumbnail.
    public function getVideoThumbnail($id);
    // Get image post's thumbnail.
    public function getImageThumbnail($id);
    // Check if a file exists.
    public function fileExists($file);
    // Upload an image, for profile pictures.
    public function uploadImage($temp_name, $target_file, $format, $resize = false, $width = 0, $height = 0);
    // Upload an image, for posts.
    public function processImage($temp_name, $new);
}