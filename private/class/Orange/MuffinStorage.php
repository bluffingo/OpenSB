<?php

namespace Orange;

use Intervention\Image\ImageManager;
use Orange\Storage;
use Symfony\Component\HttpClient\HttpClient;

class MuffinStorage implements Storage
{
    private \Symfony\Contracts\HttpClient\HttpClientInterface $muffinClient;
    private BunnyStorage $bunnyFallback;
    /**
     * @var string[]
     */
    private array $settings;

    public function __construct(Orange $orange) {
        global $muffinSettings;

        $this->muffinClient = HttpClient::create([
            'base_uri' => $muffinSettings["muffURL"],
            'headers' => [
                'Authorization' => $muffinSettings["muffAPI"],
            ],
        ]);
        $this->bunnyFallback = new BunnyStorage($orange);
        $this->settings = $muffinSettings;
    }

    // Video processing is still done under BunnyCDN due to its reliability.
    public function processVideo($new, $target_file) {
        $this->bunnyFallback->processVideo($new, $target_file);
    }

    // Video thumbnails are also done by BunnyCDN.
    public function getVideoThumbnail($id) {
        return $this->bunnyFallback->getVideoThumbnail($id);
    }

    public function getImageThumbnail($id) {
        if ($this->fileExists("/art_thumbnails/" . $id  . ".jpg")) {
            return $this->settings["muffURL"] . "/dynamic/art_thumbnails/" . $id  . ".jpg";
        }
    }

    public function fileExists($file) {
        $response = $this->muffinClient->request('GET', '/check_file.php', [
            'query' => [
                'file' => $file,
            ],
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function uploadImage($temp_name, $target_file, $format, $resize = false, $width = 0, $height = 0) {
        $path = explode('/', $target_file);

        $manager = new ImageManager();
        $img = $manager->make($temp_name);
        if ($resize) {
            $img->resize($width, $height);
        } else {
            $img->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        $img->save($temp_name, 97, $format);

        $fileHandle = fopen($temp_name, 'r');

        $response = $this->muffinClient->request('POST', '/upload_file.php', [
            'body' => [
                'name' => $target_file,
                'file' => $fileHandle,
                'folder' => $path[2], // this is shit. -bluffingo 12/23/2023
            ],
        ]);

        unlink($temp_name);
    }

    public function processImage($temp_name, $new) {
        $target_file = '/dynamic/art/' . $new . '.png';
        $target_thumbnail = '/dynamic/art_thumbnails/' . $new . '.jpg';

        Utilities::processImageSubmissionFile($temp_name, dirname(__DIR__) . '/..' . $target_file, $new);
        $fileHandle = fopen(dirname(__DIR__) . '/..' . $target_file, 'r');

        $response = $this->muffinClient->request('POST', '/upload_file.php', [
            'body' => [
                'name' => $target_file,
                'file' => $fileHandle,
                'folder' => "art",
            ],
        ]);

        unlink(dirname(__DIR__) . '/..' . $target_file);

        Utilities::processImageSubmissionThumbnail($temp_name, dirname(__DIR__) . '/..' . $target_thumbnail, $new);
        $fileHandle = fopen(dirname(__DIR__) . '/..' . $target_thumbnail, 'r');

        $response = $this->muffinClient->request('POST', '/upload_file.php', [
            'body' => [
                'name' => $target_thumbnail,
                'file' => $fileHandle,
                'folder' => "art_thumbnails",
            ],
        ]);

        unlink(dirname(__DIR__) . '/..' . $target_thumbnail);

        unlink($temp_name);
    }
}