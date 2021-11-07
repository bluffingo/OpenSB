<?php
require('lib/common.php');

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;

$feed = new Feed();

$channel = new Channel();
$channel
    ->title('squareBracket')
    ->description('Recently uploaded videos')
    ->url('http://192.168.242.1')
    ->feedUrl('http://192.168.242.1/rss.php')
    ->language('en-US')
    ->copyright('By the squareBracket contributors')
    ->pubDate(strtotime(date("Y-m-d")))
    ->lastBuildDate(strtotime(date("Y-m-d")))
    ->ttl(60)
    ->appendTo($feed);

$videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");

// Blog item
$item = new Item();
$item
    ->title('Blog Entry Title')
    ->description('<div>Blog body</div>')
    ->contentEncoded('<div>Blog body</div>')
    ->url('http://blog.example.com/2012/08/21/blog-entry/')
    ->author('John Smith')
    ->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
    ->guid('http://blog.example.com/2012/08/21/blog-entry/', true)
    ->preferCdata(true) // By this, title and description become CDATA wrapped HTML.
    ->appendTo($channel);

echo $feed->render();
