<?php


namespace squareBracket;
class VideoFavorites extends Videos
{

    public static function addFavorite($id): void
    {
        global $sql;
        $sql->query("INSERT INTO favorites (video_id, user_id) VALUES (?,?)", [$_GET['video_id'], $id]);
    }
}