<?php
namespace squareBracket;

require('lib/common.php');
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);
header('Content-Type:text/xml');

use \Suin\RSSWriter\Channel;
use \Suin\RSSWriter\Feed;
use \Suin\RSSWriter\Item;

$feed = new Feed();

$channel = new Channel();
$channel
    ->title('squareBracket')
    ->description('Recently uploaded videos')
    ->url($domain)
    ->feedUrl($domain."/rss.php")
    ->language('en-US')
    ->copyright('By the squareBracket contributors')
    ->pubDate(strtotime(date("Y-m-d")))
    ->lastBuildDate(strtotime(date("Y-m-d")))
    ->ttl(60)
    ->appendTo($feed);

$videoData = query("SELECT $userfields $videofields FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");

// Blog item
foreach ($videoData as $video) {
	$item = new Item();
	$item
	    ->title($video["title"])
	    ->description($video["description"])
	    ->contentEncoded("Description: ".$video["description"]."<br/> Views: ".$video["views"])
	    ->url($domain."/watch.php?v=".$video["video_id"])
	    ->author($video["u_name"])
	    ->pubDate(strtotime(date("Y-m-d\TH:i:s\Z", $video["time"])))
	    ->guid($domain."/watch.php?v=".$video["video_id"], true)
	    ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
	    ->appendTo($channel);
}

echo $feed->render();
