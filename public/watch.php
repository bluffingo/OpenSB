<?php

namespace openSB;

global $betty;

use \Orange\OrangeException;

require_once dirname(__DIR__) . '/private/class/common.php';

require_once dirname(__DIR__) . '/orange/classes/Pages/Submission.php';

$id = ($_GET['v'] ?? null);
$ip = getUserIpAddr();

try {
    $page = new \Orange\Pages\Submission($betty, $id);
    $data = $page->getSubmission();
} catch (OrangeException $e) {
    $e->page();
}

/*
$query = '';
$count = 0;
$commentData = $sql->query("SELECT $userfields c.comment_id, c.id, c.comment, c.author, c.date, c.deleted, (SELECT COUNT(reply_to) FROM comments WHERE reply_to = c.comment_id) AS replycount FROM comments c JOIN users u ON c.author = u.id WHERE c.id = ? ORDER BY c.date DESC", [$id]);

$relatedVideosData = $sql->query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? ORDER BY RAND() LIMIT 6", [$id]);

// move this to getVideoData
$totalLikes = $sql->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=1", [$videoData['id']]);
$totalDislikes = $sql->result("SELECT COUNT(rating) FROM rating WHERE video=? AND rating=0", [$videoData['id']]);
$combinedRatings = $totalDislikes + $totalLikes;

$allRatings = Videos::calculateRatio($totalDislikes, $totalLikes, $combinedRatings);

$totalFavorites = $sql->result("SELECT COUNT(video_id) FROM favorites WHERE video_id=?", [$videoData['video_id']]);

$allVideos = $sql->result("SELECT COUNT(id) FROM videos WHERE author=?", [$videoData['u_id']]);

if (isset($userdata['name'])) {
    if ($sql->result("SELECT * from favorites WHERE video_id = ? AND user_id = ?", [$videoData['video_id'], $userdata['id']])) {
        $isFavorited = true;
    } else {
        $isFavorited = false;
    }
    $rating = $sql->result("SELECT rating FROM rating WHERE video=? AND user=?", [$videoData['id'], $userdata['id']]);
    $subscribed = $sql->result("SELECT COUNT(user) FROM subscriptions WHERE id=? AND user=?", [$userdata['id'], $videoData['author']]);
} else {
    $isFavorited = false;
    $rating = 2;
    $subscribed = 0;
}
if ($sql->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=? AND user=?", [$videoData['video_id'], crypt($ip, $ip)])['COUNT(video_id)'] < 1) {
    $sql->query("INSERT INTO views (video_id, user) VALUES (?,?)",
        [$videoData['video_id'], crypt($ip, $ip)]);
}

$subCount = $sql->fetch("SELECT COUNT(user) FROM subscriptions WHERE user=?", [$videoData['author']])['COUNT(user)'];
$commentCount = $sql->fetch("SELECT COUNT(id) FROM comments WHERE id=?", [$videoData['video_id']])['COUNT(id)'];
$viewCount = $sql->fetch("SELECT COUNT(video_id) FROM views WHERE video_id=?", [$videoData['video_id']])['COUNT(video_id)'];

// scrapped randley layout had dumb code regarding if the video was "modern" (converted to mp4) or "legacy"
// (converted to dash), so we need to do this. actually i could do this in twig but whatever. -grkb 7/10/2022
if ($videoData['post_type'] == 0 or $videoData['post_type'] == 1) {
    $postType = "video";
} elseif ($videoData['post_type'] == 2) {
    $postType = "artwork";
} else {
    $postType = "unknown";
}

$previousRecentView = $sql->result("SELECT most_recent_view from videos WHERE video_id = ?", [$id]);
$currentTime = time();

$sql->query("UPDATE videos SET most_recent_view = ? WHERE video_id = ?", [$currentTime, $id]);
*/

$twig = new \Orange\Templating($betty);

echo $twig->render('watch.twig', [
    'submission' => $data,
]);