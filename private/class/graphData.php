<?php

namespace openSB;
class GraphData
{
    public static function getVideoGraph(): array
    {
        global $sql;
        $sql->query("SET @runningTotal = 0;");
        $videoData = $sql->query("
SELECT 
    time,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(time) AS time,
    COUNT(*) AS num_interactions
FROM videos AS e
GROUP BY DATE(FROM_UNIXTIME(e.time))) totals
ORDER BY time;");
        $videos = $sql->fetchArray($videoData);
        return $videos;
    }

    public static function getCommentGraph(): array
    {
        global $sql;
        $sql->query("SET @runningTotal = 0;");
        $videoData = $sql->query("
SELECT 
    date,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(date) AS date,
    COUNT(*) AS num_interactions
FROM comments AS e
GROUP BY DATE(FROM_UNIXTIME(e.date))) totals
ORDER BY date;");
        $videos = $sql->fetchArray($videoData);
        return $videos;
    }

    public static function getUserGraph(): array
    {
        global $sql;
        $sql->query("SET @runningTotal = 0;");
        $userData = $sql->query("
SELECT 
    joined,
    num_interactions,
    @runningTotal := @runningTotal + totals.num_interactions AS runningTotal
FROM
(SELECT 
    FROM_UNIXTIME(joined) AS joined,
    COUNT(*) AS num_interactions
FROM users AS e
GROUP BY DATE(FROM_UNIXTIME(e.joined))) totals
ORDER BY joined;");
        $users = $sql->fetchArray($userData);
        return $users;
    }
}