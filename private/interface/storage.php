<?php

namespace openSB;

interface Storage
{
    public function __construct();
    public function processVideo($new, $target_file);
    public function getVideoThumbnail($id);
}