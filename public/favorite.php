<?php
namespace squareBracket;

require dirname(__DIR__) . '/private/class/common.php';

if (isset($_GET['video_id'])) {
    if (!$sql->result("SELECT * from favorites WHERE video_id = ? AND user_id = ?", [$_GET['video_id'], $userdata['id']])) {
        VideoFavorites::addFavorite($userdata['id']);
    } else {
        die("You've already favorited this video!");
    }
} else {
    die("invalid");
}
