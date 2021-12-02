<?php
/* 
CUSTOMIZABLE GET_VIDEO_INFO
by CrazyFrog#0001 (667891621236310016)

Supported video types:
video/mp4 with codecs
video/webm with codecs
video/x-flv no codecs required, only used on Flash Player

Supported qualities:
tiny:144p,
light:144p,
small:240p,
medium:360p,
large:480p,
hd720:720p,
hd1080:1080p,
highres:8192 (4k)

ITAGs and containers:
https://gist.github.com/sidneys/7095afe4da4ae58694d128b1034e01e2

special thanks to john for giving me the 2013 player
-gamerappa 11/7/2021
*/
require('lib/common.php');
$id = (isset($_GET['video_id']) ? $_GET['video_id'] : null);
$videoData = fetch("SELECT $userfields v.* FROM videos v JOIN users u ON v.author = u.id WHERE v.video_id = ?", [$id]);
// these are all the videos, with their settings, and formats
$fmt_stream_map = [
	[
	"itag" => "43",
    "url" => './videos/' . $_GET['video_id'] . '.converted.mp4',
    "sig" => "gordon.freeman.died.after.he.waited.for.subrocks.2012",
    "quality" => "hd720",
    "type" => "video/mp4; codecs='avc1.4d002a'"
	],
	[
	"itag" => "43",
    "url" => './videos/' . $_GET['video_id'] . '.converted.mp4',
    "sig" => "21EDBD12A97AC6CFE5B49224A5AD622895FFADEB.913D0D8ADC3EB8203CA6E08F616AC4B63156EC64",
    "fallback_host" => "tc.v14.cache3.c.youtube.com",
    "quality" => "hd720",
    "type" => "video/webm; codecs=\"vp8.0, vorbis\""
	],
];

$count = 0;
$url_encoded_fmt_stream_map;
foreach($fmt_stream_map as $stream) {
    if($count == 0)
        $url_encoded_fmt_stream_map = http_build_query($stream);
    else
        $url_encoded_fmt_stream_map = $url_encoded_fmt_stream_map . "," . http_build_query($stream);
    $count++;
}

// other data, such as title, author, view count, video id, keywords
$main_data = array(
    "hl" => "en_US",
    "cc_module" => "http://s.ytimg.com/yts/swfbin/subtitle_module-vflanTIik.swf",
    "track_embed" => "0",
    "video_verticals" => "[933, 8, 930]",
    "vq" => "auto",
    "title" => $videoData['title'],
    "sendtmp" => "1",
    "avg_rating" => "5.0",
    "ttsurl" => "http://www.youtube.com/api/timedtext?signature=E949556D4478C57B95F0E4268F8D85D460D27DF6.1EC1679B0E1466625B2A029D836BE9C135A0B300&hl=en_US&caps=asr&expire=1361228889&sparams=asr_langs%2Ccaps%2Cv%2Cexpire&v=LHdA7Yc-8Rg&key=yttt1&asr_langs=ko%2Cde%2Cpt%2Cen%2Cnl%2Cja%2Cru%2Ces%2Cfr%2Cit",
    "url_encoded_fmt_stream_map" => $url_encoded_fmt_stream_map,
	"plid" => "AATWAe5iQ_FjAV-I",
    "view_count" => "24465",
    "cc_asr" => "1",
    "token" => "vjVQa1PpcFPQw_h19VxFJZdJZbKkh5-obrhC9M93j-E=",
    "no_get_video_log" => "1",
    "muted" => "0",
    "allow_ratings" => "1",
    "keywords" => "renegade,black,dawn,release,C&C,cnc,ut3,unreal,tournament,msuc,udk,development,kit,ion,cannon,mod",
    "account_playback_token" => "",
    "video_id" => "LHdA7Yc-8Rg",
    "thumbnail_url" => "http://i2.ytimg.com/vi/LHdA7Yc-8Rg/default.jpg",
    "status" => "ok",
    "has_cc" => "True",
    "fexp" => "907063,919329,913565,920704,912806,902000,922403,922405,929901,913605,925006,908529,920201,930101,911116,926403,910221,901451,919114",
    "ftoken" => "",
    "iurlsd" => "http://i2.ytimg.com/vi/LHdA7Yc-8Rg/sddefault.jpg",
    "cc_font" => "Arial Unicode MS, arial, verdana, _sans",
    "pltype" => "contentugc",
    "allow_embed" => "1",
    "author" => $videoData['u_name'],
    "length_seconds" => "0",
    "storyboard_spec" => "",
    "abd" => "1",
    "iurlmaxres" => "http://i2.ytimg.com/vi/LHdA7Yc-8Rg/maxresdefault.jpg",
    "watermark" => ",http://s.ytimg.com/yts/img/watermark/youtube_watermark-vflHX6b6E.png,http://s.ytimg.com/yts/img/watermark/youtube_hd_watermark-vflAzLcD6.png",
    "cc3_module" => "http://s.ytimg.com/yts/swfbin/subtitles3_module-vflfzxB9O.swf",
    "tmi" => "1",
    "ptk" => "youtube_none",
    "endscreen_module" => "http://s.ytimg.com/yts/swfbin/endscreen-vfl4_CAIR.swf",
    "fmt_list" => "45/1280x720/99/0/0,22/1280x720/9/0/115,44/854x480/99/0/0,35/854x480/9/0/115,43/640x360/99/0/0,34/640x360/9/0/115,18/640x360/9/0/115,5/320x240/7/0/0,36/320x240/99/0/0,17/176x144/99/0/0",
    "timestamp" => "1361203689",
	"src" => './videos/' . $videoData['video_id'] . '.converted.mp4',
);

$main_data = http_build_query($main_data);

die(trim($main_data));