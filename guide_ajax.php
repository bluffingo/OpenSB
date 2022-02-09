<?php
// this is probably one of the worst pieces of code i've ever made.
// bhief, you're allowed to scream profanites at me. -gr 10/28/2021
header('Content-type: application/json');
$rawOutputRequired = true;
// tabs: from squarebracket, trending, popular and music
if (isset($_GET['action_load_system_feed']))
{
    switch ($_GET['feed_type'])
    {
        case "youtube":
            $type = 0;
        break;
        case "popular":
            $type = 1;
        break;
        case "trending":
            $type = 2;
        break;
        case "music":
            $type = 3;
        break;
    }
    require ('lib/common.php');
    $twig = twigloader();
    switch ($type)
    {
        case 0:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.id DESC LIMIT 10");
            $icon = "youtube";
            $title = "From squareBracket";
        break;
        case 1:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.views DESC LIMIT 10");
            $icon = "popular";
            $title = "Popular";
        break;
        case 2:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.views DESC LIMIT 10");
            $icon = "trending";
            $title = "Trending";
        break;
        case 3:
            // todo: figure out how the fuck should we do this with music
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id ORDER BY v.views DESC LIMIT 10");
            $icon = "music";
            $title = "Music";
        break;
        default:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.author, v.videolength FROM videos v JOIN users u ON v.author = u.id WHERE NOT v.video_id = ? AND NOT v.flags = 0010 AND NOT v.flags = 0020 ORDER BY RAND() LIMIT 6", [$videoID]);
        break;
    }
?>
{'paging': null, 'feed_html': `
<div class=\'feed-header no-metadata before-feed-content\'>
	<div class=\'feed-header-thumb\'>
		<img class=\'feed-header-icon <?php echo $icon; ?>\' src=\'//web.archive.org/web/20120118121554im_/http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif\' alt=\'\'>
	</div>
	<div class=\'feed-header-details\'>
		<h2>
			<?php echo $title; ?>
		</h2>
	</div>
</div>
<div class=\'feed-container\' data-filter-type=\'\' data-view-type=\'\'>
<div class=\'feed-page\'>
<ul>
<?php
    echo $twig->render('components/feed_list.twig', ['videos' => $videoData, ]);
?>
</ul>
</div>
</div>
`}
<?php
}
if (isset($_GET['action_load_chart_feed']))
{
    switch ($_GET['chart_name'])
    {
        case "entertainment":
            $type = 0;
        break;
        case "news":
            $type = 1;
        break;
        case "comedy":
            $type = 2;
        break;
        case "science":
            $type = 3;
        break;
        case "gadgets":
            $type = 4;
        break;
        case "people":
            $type = 5;
        break;
        case "education":
            $type = 6;
        break;
    }
    require ('lib/common.php');
    switch ($type)
    {
        case 0:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 1 ORDER BY v.id DESC LIMIT 10");
            $icon = "entertainment";
            $title = "Entertainment";
        break;
        case 1:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 2 ORDER BY v.views DESC LIMIT 10");
            $icon = "news";
            $title = "News & Updates";
        break;
        case 2:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 3 ORDER BY v.views DESC LIMIT 10");
            $icon = "comedy";
            $title = "Shitposting & Comedy";
        break;
        case 3:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 4 ORDER BY v.views DESC LIMIT 10");
            $icon = "science";
            $title = "Science & Technology";
        break;
        case 4:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 5 ORDER BY v.views DESC LIMIT 10");
            $icon = "gadgets";
            $title = "Gaming";
        break;
        case 5:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 6 ORDER BY v.views DESC LIMIT 10");
            $icon = "people";
            $title = "Life";
        break;
        case 6:
            $videoData = query("SELECT $userfields v.video_id, v.title, v.description, v.time, v.views, v.videolength, v.tags, v.category_id, v.author FROM videos v JOIN users u ON v.author = u.id WHERE `category_id` = 7 ORDER BY v.views DESC LIMIT 10");
            $icon = "education";
            $title = "Archive Dump";
        break;
    }
    $twig = twigloader();
?>
{'paging': null, 'feed_html': `
<div class=\'feed-header no-metadata before-feed-content\'>
	<div class=\'feed-header-thumb\'>
		<img class=\'feed-header-icon <?php echo $icon; ?>\' src=\'//web.archive.org/web/20120118121554im_/http://s.ytimg.com/yt/img/pixel-vfl3z5WfW.gif\' alt=\'\'>
	</div>
	<div class=\'feed-header-details\'>
		<h2>
			<?php echo $title; ?>
		</h2>
	</div>
</div>
<div class=\'feed-container\' data-filter-type=\'\' data-view-type=\'\'>
<div class=\'feed-page\'>
<ul>
<?php
    echo $twig->render('components/feed_list.twig', ['videos' => $videoData, ]);
?>
</ul>
</div>
</div>
`}
<?php
}
?>