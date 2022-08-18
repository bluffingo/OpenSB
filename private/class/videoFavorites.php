<?php


namespace squareBracket;
class VideoFavorites extends Videos
{

    public static function addFavorite($vid, $uid): void
    {
        global $sql;
        $sql->query("INSERT INTO favorites (video_id, user_id) VALUES (?,?)", [$vid, $uid]);
    }
}