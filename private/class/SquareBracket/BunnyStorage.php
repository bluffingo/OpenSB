<?php

namespace SquareBracket;

use Core\Database;
use ToshY\BunnyNet\Client\BunnyClient;
use ToshY\BunnyNet\EdgeStorageAPI;
use ToshY\BunnyNet\Enum\Region;
use ToshY\BunnyNet\Exception\BunnyClientResponseException;
use ToshY\BunnyNet\StreamAPI;

class BunnyStorage implements Storage
{
    private BunnyClient $bunnyClient;
    private EdgeStorageAPI $edgeStorageApi;
    private mixed $streamLibrary;
    private mixed $streamHostname;
    private mixed $storageZone;
    private mixed $pullZone;
    private Database $database;

    public function __construct(SquareBracket $orange) {
        global $bunnySettings;

        $this->bunnyClient = new BunnyClient(
            client: new \Symfony\Component\HttpClient\Psr18Client(),
        );
        $this->edgeStorageApi = new EdgeStorageAPI(
            apiKey: $bunnySettings["storageApi"],
            client: $this->bunnyClient,
            region: Region::UK, //FIXME: don't hardcode this. -grkb 4/7/2023
        );
        $this->streamLibrary = $bunnySettings["streamLibrary"];
        $this->streamHostname = $bunnySettings["streamHostname"];
        $this->storageZone = $bunnySettings["storageZone"];
        $this->pullZone = $bunnySettings["pullZone"];
        $this->database = $orange->getDatabase();
    }

    public function processVideo($new, $target_file): void
    {
        global $bunnySettings;

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
        $content = file_get_contents($target_file);
        $streamApi->uploadVideo(
            libraryId: $this->streamLibrary,
            videoId: $newVideo->getContents()["guid"],
            body: $content,
            query: [
                'enabledResolutions' => '240p,360p,480p,720p',
            ],
        );
        $this->database->query("UPDATE videos SET videofile = ?, videolength = ?, flags = ? WHERE video_id = ?", [$newVideo->getContents()["guid"], 0, 0, $new]);
        unlink($target_file);
    }

    public function getVideoThumbnail($id): string
    {
        $guid = $this->database->fetch("SELECT videofile from videos where video_id = ?", [$id]);
        return "https://" . $this->streamHostname . "/" . $guid["videofile"] . "/thumbnail.jpg";
    }

    public function getImageThumbnail($id): string
    {
        return "https://" . $this->pullZone . "/dynamic/art_thumbnails/" . $id  . ".jpg";
    }

    public function fileExists($file): bool
    {
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

    public function processImage($temp_name, $new): void
    {
        $target_file = '/dynamic/art/' . $new . '.png';
        $target_thumbnail = '/dynamic/art_thumbnails/' . $new . '.jpg';

        $target_file_local = SB_DYNAMIC_PATH . '/art/' . $new . '.png';
        $target_thumbnail_local = SB_DYNAMIC_PATH . '/art_thumbnails/' . $new . '.jpg';

        UnorganizedFunctions::processImageSubmissionFile($temp_name, $target_file_local);
        $content = file_get_contents($target_file_local);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_file,
            body: $content,
        );
        unlink($target_file_local);

        UnorganizedFunctions::processImageSubmissionThumbnail($temp_name, $target_thumbnail_local);
        $content = file_get_contents($target_thumbnail_local);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_thumbnail,
            body: $content,
        );
        unlink($target_thumbnail_local);

        unlink($temp_name);
    }

    public function uploadProfilePicture($temp_name, $new): void
    {
        $target_file = '/dynamic/pfp/' . $new . '.png';

        $target_file_local = SB_DYNAMIC_PATH . '/pfp/' . $new . '.png';

        UnorganizedFunctions::processProfilePicture($temp_name, $target_file_local);
        $content = file_get_contents($target_file_local);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_file,
            body: $content,
        );
        unlink($target_file_local);

        unlink($temp_name);
    }

    public function uploadCustomThumbnail($temp_name, $new): void
    {
        $target_file = '/dynamic/custom_thumbnails/' . $new . '.png';

        $target_file_local = SB_DYNAMIC_PATH . '/custom_thumbnails/' . $new . '.png';

        UnorganizedFunctions::processCustomThumbnail($temp_name, $target_file_local);
        $content = file_get_contents($target_file_local);
        $this->edgeStorageApi->uploadFile(
            storageZoneName: $this->storageZone,
            fileName: $target_file,
            body: $content,
        );
        unlink($target_file_local);

        unlink($temp_name);
    }
}