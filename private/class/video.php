<?php

namespace openSB;
class Videos
{
    // This is for the like-to-dislike ratio lightsaber. -Gamerappa, November 2nd 2021
    public static function calculateRatio($number, $percent, $total)
    {
        // If there's no ratio or dislikes, it returns 100.
        if ($total == 0 or $number == 0) {
            return 100;
        } else {
            // It returns the Like-to-dislike ratio.
            return ($percent / $total) * $number * 100;
        }
    }

    /**
     * Return the randomized ID of a video.
     *
     * @param string $video The randomized video ID.
     * @return string the ID of a video.
     */
    public static function getVideoRanID($video): string
    {
        global $sql;
        return $sql->result("SELECT video_id FROM videos WHERE id = ?", [$video]);
    }

    public static function addVideo($new, $title, $description, $id, string $upload_file): void
    {
        global $sql;
        $sql->query("INSERT INTO videos (video_id, title, description, author, time, most_recent_view, videofile, flags) VALUES (?,?,?,?,?,?,?,?)", [$new, $title, $description, $id, time(), time(), $upload_file, 0x2]);
    }

    public static function getVideoFile($videoData)
    {
        global $isQoboTV, $bunnySettings;
        if ($isQoboTV) {
            if ($videoData['post_type'] == 0) {
                // videofile on videos using bunnycdn are the guid, don't ask me why. -grkb 4/8/2023
                $bunny_url = "https://" . $bunnySettings["streamHostname"] . "/" . $videoData["videofile"] . "/playlist.m3u8";
            } elseif ($videoData['post_type'] == 2) {
                // https://qobo-grkb.b-cdn.net/dynamic/art/f_eKEJNj4bm.png
                $bunny_url = "https://" . $bunnySettings["pullZone"] . $videoData["videofile"];
            }
            return $bunny_url;
        } else {
            return $videoData['videofile'];
        }
    }

    public static function getVideoData($userfields, $id)
    {
        trigger_error('Method ' . __METHOD__ . ' is deprecated', E_USER_DEPRECATED);
        global $sql, $isQoboTV, $bunnySettings;
        $videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
        if (!$videoData) {
            return false;
        }
        $videoData['views'] = $sql->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$videoData['video_id']]) ['COUNT(video_id)'];
        $videoData['file'] = Videos::getVideoFile($videoData);
        return $videoData;
    }
}