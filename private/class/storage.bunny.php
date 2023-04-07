<?php

namespace openSB;

use ToshY\BunnyNet\StreamAPI;
use ToshY\BunnyNet\Client\BunnyClient;

class BunnyStorage implements Storage
{
    public function __construct() { 
        global $bunnySettings;

        $this->bunnyClient = new BunnyClient(
            client: new \Symfony\Component\HttpClient\Psr18Client(),
        );
        $this->streamApi = new StreamAPI(
            apiKey: $bunnySettings["streamApi"],
            client: $this->bunnyClient
        );
        $this->streamLibrary = $bunnySettings["streamLibrary"];
        $this->hostName = $bunnySettings["cdnHostname"];
    }

    public function processVideo($new, $target_file) {
        global $sql;

        $newVideo = $this->streamApi->createVideo(
            libraryId: $this->streamLibrary,
            body: [
                'title' => 'Qobo: ' . $new,
            ],
        );
        $this->streamApi->uploadVideo(
            libraryId: $this->streamLibrary,
            videoId: $newVideo->getContents()["guid"],
            localFilePath: $target_file,
            query: [
                'enabledResolutions' => '240p,360p,480p,720p',
            ],
        );
        $sql->query("UPDATE videos SET videofile = ?, videolength = ?, flags = ? WHERE video_id = ?", [$newVideo->getContents()["guid"], 0, 0, $new]);
    }

    public function getVideoThumbnail($id) {
        global $sql;

        $guid = $sql->fetch("SELECT videofile from videos where video_id = ?", [$id]);
        return "https://" . $this->hostName . "/" . $guid["videofile"] . "/thumbnail.jpg";
    }
}