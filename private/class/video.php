<?php

namespace squareBracket;
class Videos
{
    // this is like that so that it stays readable in the code and doesn't introduce a fucking huge horizontal scrollbar on GitHub. -grkb 3/31/2022
    //why the hell is it using tutorial names?
    public static $recommendedfields = "
		jaccard.video_id,
		jaccard.flags,
		jaccard.intersect,
		jaccard.union,
		jaccard.intersect / jaccard.union AS 'jaccard index'
	FROM
		(
		SELECT
			c2.video_id AS video_id,
			c2.flags AS flags,
			COUNT(ct2.tag_id) AS 'intersect',
			(
			SELECT
				COUNT(DISTINCT ct3.tag_id)
			FROM
				tag_index ct3
			WHERE
				ct3.video_id IN(c1.id, c2.id)
		) AS 'union'
	FROM
		videos AS c1
	INNER JOIN videos AS c2
	ON
		c1.id != c2.id
	LEFT JOIN tag_index AS ct1
	ON
		ct1.video_id = c1.id
	LEFT JOIN tag_index AS ct2
	ON
		ct2.video_id = c2.id AND ct1.tag_id = ct2.tag_id
	WHERE
		c1.id = ?
	GROUP BY
		c1.id,
		c2.id
	) AS jaccard
	WHERE
		jaccard.flags != 0x2
	ORDER BY
		jaccard.intersect / jaccard.union
	DESC";

    // Functions related to sbNext Video Stuff.
    static function categoryIDToName($id)
    {
        switch ($id) {
            case 0:
                $name = __('Miscellaneous');
                break;
            case 1:
                $name = __('Entertainment');
                break;
            case 2:
                $name = __('Comedy & Humor');
                break;
            case 3:
                $name = __('Gaming');
                break;
            case 4:
                $name = __('News and Information');
                break;
            case 5:
                $name = __('Lifestyle');
                break;
            case 6:
                $name = __('Science & Technology');
                break;
            case 7:
                $name = __('Archival');
                break;
        }
        return $name;
    }

    static function type_to_cat($type)
    {
        switch ($type) {
            case 'misc':
                $cat = 0;
                break;
            case 'entertainment':
                $cat = 1;
                break;
            case 'comedy':
                $cat = 2;
                break;
            case 'gaming':
                $cat = 3;
                break;
            case 'news':
                $cat = 4;
                break;
            case 'life':
                $cat = 5;
                break;
            case 'technology':
                $cat = 6;
                break;
            case 'backup':
                $cat = 7;
                break;
        }
        return $cat;
    }

    // This is for the like-to-dislike ratio lightsaber. -Gamerappa, November 2nd 2021
    static function calculateRatio($number, $percent, $total)
    {
        // If there's no ratio or dislikes, it returns 100.
        if ($total == 0 or $number == 0) {
            return 100;
        } else {
            // It returns the Like-to-dislike ratio.
            return ($percent / $total) * $number * 100;
        }
    }

    static function videofields(): string
    {
        //todo: make this cleaner.
        return 'v.id, v.video_id, v.title, v.description, v.time, (SELECT COUNT(*) FROM views WHERE video_id = v.video_id) AS views, (SELECT COUNT(*) FROM comments WHERE id = v.video_id) AS comments, (SELECT COUNT(*) FROM favorites WHERE video_id = v.video_id) AS favorites, (SELECT COUNT(*) FROM favorites WHERE video_id = v.video_id) AS favorites, v.videolength, v.category_id, v.author';
    }

    /**
     * Return a list of videos, Limit and order is required.
     *
     * @param string $orderBy A column in the videos table.
     * @param int $limit The limit.
     * @param string $whereSomething Precise what column.
     * @param string $whereEquals Precise the value of the column.
     * @return array A video list, ordered by what $orderBy specified.
     */
    static function getVideos($orderBy, $limit, $whereSomething = null, $whereEquals = null): array
    {
        global $userfields, $videofields, $sql;
        if (isset($whereSomething)) {
            $videoList = $sql->fetchArray($sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE $whereSomething = ? AND flags != 0x2 ORDER BY $orderBy LIMIT $limit", [$whereEquals]));
        } else {
            $videoList = $sql->fetchArray($sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE flags != 0x2 ORDER BY $orderBy LIMIT $limit"));
        }
        foreach ($videoList as &$video) {
            $video['tags'] = VideoTags::getVideoTags($video['id']);
        }
        return $videoList;
    }

    /**
     * Return a list of videos that are simillar to the video the user is watching.
     *
     * @param string $videoID The ID of the currently watched video.
     * @return array A video list, ordered by "relevancy".
     */

    static function getRecommended($videoID): array
    {
        global $userfields, $videofields, $recommendedfields, $sql;
        $recommendfields = self::$recommendedfields;
        $intID = self::getVideoIntID($videoID);
        $recommendedList = $sql->fetchArray($sql->query("SELECT $recommendfields LIMIT 20", [$intID]));
        $videoList = array();
        foreach ($recommendedList as $row) {
            //$videoData = fetch("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$row['video_id']]);
            $videoData = self::fetchVideos("v.video_id", $row['video_id']);
            array_push($videoList, $videoData);
        }
        return $videoList;
    }

    /**
     * Return the interger ID of a video.
     *
     * @param string $video The randomized video ID.
     * @return int the ID of a video.
     */
    static function getVideoIntID($video): int
    {
        global $sql;
        return $sql->result("SELECT id FROM videos WHERE video_id = ?", [$video]);
    }

    /**
     * Return the randomized ID of a video.
     *
     * @param string $video The randomized video ID.
     * @return string the ID of a video.
     */
    static function getVideoRanID($video): string
    {
        global $sql;
        return $sql->result("SELECT video_id FROM videos WHERE id = ?", [$video]);
    }

    /**
     * Return a list of videos in an alternative way.
     *
     * @param string $whereSomething Precise what column.
     * @param string $whereEquals Precise the value of the column.
     * @return array A video list, ordered by what $orderBy specified.
     */
    static function fetchVideos($whereSomething, $whereEquals, $orderBy = null, $limit = null)
    {
        global $userfields, $videofields, $sql;
        if (isset($orderBy, $limit)) {
            $videoList = $sql->fetch("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE $whereSomething = ? AND flags != 0x2 ORDER BY $orderBy LIMIT $limit", [$whereEquals]);
        } elseif (isset($orderBy)) {
            $videoList = $sql->fetch("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE $whereSomething = ? AND flags != 0x2 ORDER BY $orderBy", [$whereEquals]);
        } elseif (isset($limit)) {
            $videoList = $sql->fetch("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE $whereSomething = ? AND flags != 0x2 LIMIT $limit", [$whereEquals]);
        } else {
            $videoList = $sql->fetch("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE $whereSomething = ? AND flags != 0x2", [$whereEquals]);
        }
        return $videoList;
    }

    static function getLatestVideo($userID)
    {
        $video = Videos::fetchVideos("author", $userID, "v.id DESC", 1);
        return $video;
    }

    /**
     * Return the link to the FLV version of the video.
     *
     * @param string $videoID The ID of the currently watched video.
     * @return string A link to the FLV version of the video, or if nothing is inputted, an error.
     */
    static function getFlashVideo($videoID): string
    {
        if (isset($videoID) ? $videoID : null) {
            $file = "/media/" . $videoID . ".flv";
            return $file;
        } else {
            die("getFlashVideo Error: videoID is missing!");
        }
    }

    static function addVideo($new, $title, $description, $id, string $upload_file): void
    {
        global $sql;
        $sql->query("INSERT INTO videos (video_id, title, description, author, time, most_recent_view, videofile, flags) VALUES (?,?,?,?,?,?,?,?)", [$new, $title, $description, $id, time(), time(), $upload_file, 0x2]);
    }

    static function bumpVideo(int $currentTime, $id): void
    {
        global $sql;
        $sql->query("UPDATE videos SET most_recent_view = ? WHERE video_id = ?", [$currentTime, $id]);
    }

    static function getVideoData($userfields, $id)
    {
        global $sql;
        $videoData = $sql->fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
        if (!$videoData) error('404', __("The video you were looking for cannot be found."));
        $videoData['views'] = $sql->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$videoData['video_id']]) ['COUNT(video_id)'];
        return $videoData;
    }

    static function getFavoritedVideosFromUser(string $limit, $id): array
    {
        global $sql, $userfields, $videofields;
        $videoData = $sql->query("
SELECT $userfields $videofields FROM videos v 
	JOIN users u ON v.author = u.id 
	JOIN favorites f ON (f.video_id = v.video_id) 
	WHERE 
		f.user_id = ?
	AND 
		flags != 0x2
ORDER BY v.time DESC $limit", [$id]);
        $videos = $sql->fetchArray($videoData);
        foreach ($videos as &$video) {
            $video['tags'] = VideoTags::getVideoTags($video['id']);
        }
        return $videos;
    }
}