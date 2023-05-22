<?php

namespace openSB;

use \Intervention\Image\ImageManager;

use ToshY\BunnyNet\Client\BunnyClient;
use ToshY\BunnyNet\EdgeStorageAPI;
use ToshY\BunnyNet\Enum\Region;
use ToshY\BunnyNet\Exception\BunnyClientResponseException;
use ToshY\BunnyNet\StreamAPI;

class BunnyStorage implements Storage
{
    public function __construct() { 
        global $bunnySettings;

        $this->bunnyClient = new BunnyClient(
            client: new \Symfony\Component\HttpClient\Psr18Client(),
        );
        $this->edgeStorageApi = new EdgeStorageAPI(
            apiKey: $bunnySettings["storageApi"],
            region: Region::UK, //FIXME: don't hardcode this. -grkb 4/7/2023
            client: $this->bunnyClient,
        );
        $this->streamLibrary = $bunnySettings["streamLibrary"];
        $this->streamHostname = $bunnySettings["streamHostname"];
        $this->storageZone = $bunnySettings["storageZone"];
        $this->pullZone = $bunnySettings["pullZone"];
    }

    public function processVideo($new, $target_file) {
        global $sql, $bunnySettings;

        // this fucking shit won't work if i put this on __construct(). -grkb 4/7/2023
        $streamApi = new StreamAPI(
            apiKey: $bunnySettings["streamApi"],
            client: $this->bunnyClient,
        );

        $newVideo = $streamApi->createVideo(
            libraryId: $this->streamLibrary,
            body: [
                'title' => 'Qobo: ' . $new,
            ],
        );
        $streamApi->uploadVideo(
            libraryId: $this->streamLibrary,
            videoId: $newVideo->getContents()["guid"],
            localFilePath: $target_file,
            query: [
                'enabledResolutions' => '240p,360p,480p,720p',
            ],
        );
        $sql->query("UPDATE videos SET videofile = ?, videolength = ?, flags = ? WHERE video_id = ?", [$newVideo->getContents()["guid"], 0, 0, $new]);
        unlink($target_file);
    }

    public function getVideoThumbnail($id) {
        global $sql;

        $guid = $sql->fetch("SELECT videofile from videos where video_id = ?", [$id]);
        return "https://" . $this->streamHostname . "/" . $guid["videofile"] . "/thumbnail.jpg";
    }

    public function getImageThumbnail($id) {
        return "https://" . $this->pullZone . "/dynamic/art_thumbnails/" . $id  . ".jpg";
    }

    public function fileExists($file) {
        try {
            $file = $this->edgeStorageApi->downloadFile(
                storageZoneName: $this->storageZone,
                fileName: $file,
            );
        } catch (BunnyClientResponseException $e) {
            return false;
        }
        return true;
    }
    public function uploadImage($temp_name, $target_file, $format, $resize = false, $width = 0, $height = 0) {
        $manager = new ImageManager();
        $img = $manager->make($temp_name);
        if ($resize) {
            $img->resize($width, $height);
        }
        $img->save($temp_name, 0, $format);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_file,
            localFilePath: $temp_name,
        );
        unlink($temp_name);
    }

    public function processImage($temp_name, $new) {
        $manager = new ImageManager();
        $target_file = '/dynamic/art/' . $new . '.png';
        $target_thumbnail = '/dynamic/art_thumbnails/' . $new . '.jpg';
        
        $img = $manager->make($temp_name);
        $img->resize(2048, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save(dirname(__DIR__) . '/..' . $target_file);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_file,
            localFilePath: dirname(__DIR__) . '/..' . $target_file,
        );
        unlink(dirname(__DIR__) . '/..' . $target_file);

        $img = $manager->make($temp_name)->encode('jpg', 80);
        $img->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $img->save(dirname(__DIR__) . '/..' . $target_thumbnail);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_thumbnail,
            localFilePath: dirname(__DIR__) . '/..' . $target_thumbnail,
        );
        unlink(dirname(__DIR__) . '/..' . $target_thumbnail);
    }

    // DO NOT USE THIS OUTSIDE OF CATTLEDOG SCRIPTS
    public function cdUpload($local_file_path, $file_path) {
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $local_file_path,
            localFilePath: $file_path,
        );
    }
}