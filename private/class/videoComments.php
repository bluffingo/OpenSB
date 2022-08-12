<?php

namespace squareBracket;
class VideoComments extends Videos
{
    static function addComment($videoID, $comment, $id)
    {
        global $sql;
        $sql->query("INSERT INTO comments (id, reply_to, comment, author, date, deleted) VALUES (?,?,?,?,?,?)",
            [$videoID, 0, $comment, $id, time(), 0]);
    }

    static function getComments($videoID): array
    {
        global $sql, $userfields;
        $videoComments = $sql->fetchArray($sql->query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$videoID]));
        foreach ($videoComments as &$comment) {
            $comment['allVideos'] = Users::getUserVideoCount($comment['author']);
            $comment['allFavorites'] = Users::getUserFavoriteCount($comment['author']);
        }
        return $videoComments;
    }
}