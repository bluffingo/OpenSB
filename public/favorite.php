<?php

namespace openSB;

$rawOutputRequired = true;
require_once dirname(__DIR__) . '/private/class/common.php';

if ($userbandata) {
    error(403, __("You are currently banned and cannot proceed with this action."));
}

if (isset($_POST['video_id'])) {
    if (!$sql->result("SELECT * from favorites WHERE video_id = ? AND user_id = ?", [$_POST['video_id'], $userdata['id']])) {
        VideoFavorites::addFavorite($_POST['video_id'], $userdata['id']);
        echo 1;
    } else {
        die("You've already favorited this video!");
        echo 1;
    }
} else {
    die("invalid");
}
