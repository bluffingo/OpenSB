<?php

namespace SquareBracket;

use ToshY\BunnyNet\StreamAPI;

class Storage
{
    private bool $chazizInstance;
    private array $bunnyCDNSettings;
    public function __construct(bool $isChazizSB, array $bunnySettings) {
        if ($isChazizSB) {
            $this->chazizInstance = true;
            $this->bunnyCDNSettings = $bunnySettings;
        } else {
            $this->chazizInstance = false;
            $this->bunnyCDNSettings = [];
        }
    }

    public function processVideo($new, $target_file): void
    {
        if ($this->chazizInstance) {
            // this fucking shit won't work if i put this on __construct(). -grkb 4/7/2023
            $streamApi = new StreamAPI(
                apiKey: $this->bunnyCDNSettings["streamApi"],
                client: $this->bunnyClient,
            );

            $newVideo = $streamApi->createVideo(
                libraryId: $this->bunnyCDNSettings["streamLibrary"],
                body: [
                    'title' => 'squareBracket: ' . $new,
                ],
            );
            $content = file_get_contents($target_file);
            $streamApi->uploadVideo(
                libraryId: $this->bunnyCDNSettings["streamLibrary"],
                videoId: $newVideo->getContents()["guid"],
                body: $content,
                query: [
                    'enabledResolutions' => '240p,360p,480p,720p',
                ],
            );
            $this->database->query("UPDATE videos SET videofile = ?, videolength = ?, flags = ? WHERE video_id = ?", [$newVideo->getContents()["guid"], 0, 0, $new]);
            unlink($target_file);
        } else {
            if (str_starts_with(php_uname(), "Windows")) {
                pclose(popen(sprintf('start /B  php %s "%s" "%s" "1" > %s', SB_PRIVATE_PATH . '\scripts\processingworker.php', $new, $target_file, SB_DYNAMIC_PATH . '/videos/' . $new . '.log'), "r"));
            } else {
                system(sprintf('php %s "%s" "%s" "1" > %s 2>&1 &', SB_PRIVATE_PATH . '/scripts/processingworker.php', $new, $target_file, SB_DYNAMIC_PATH . '/videos/' . $new . '.log'));
            }
        }
    }

    public function getVideoThumbnail($id): string
    {
        global $branding;

        if ($this->chazizInstance) {
            $guid = $this->database->fetch("SELECT videofile from videos where video_id = ?", [$id]);
            return "https://" . $this->streamHostname . "/" . $guid["videofile"] . "/thumbnail.jpg";
        } else {
            if (file_exists('../dynamic/thumbnails/' . $id . '.png')) {
                return '../dynamic/thumbnails/' . $id . '.png';
            } else {
                return $branding["assets_location"] . '/placeholder.png';
            }
        }
    }

    public function getImageThumbnail($id): string
    {
        global $branding;

        if  (file_exists('../dynamic/art_thumbnails/' . $id . '.jpg')) {
            return '../dynamic/art_thumbnails/' . $id . '.jpg';
        } else {
            return $branding["assets_location"] . '/placeholder.png';
        }
    }

    public function fileExists($file): bool
    {
        return file_exists($file);
    }

    public function processImage($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/art/' . $new . '.png';
        $target_thumbnail = SB_DYNAMIC_PATH . '/art_thumbnails/' . $new . '.jpg';

        UnorganizedFunctions::processImageSubmissionFile($temp_name, $target_file);
        UnorganizedFunctions::processImageSubmissionThumbnail($temp_name, $target_thumbnail);

        unlink($temp_name);
    }

    public function uploadProfilePicture($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/pfp/' . $new . '.png';

        UnorganizedFunctions::processProfilePicture($temp_name, $target_file);

        unlink($temp_name);
    }

    public function uploadCustomThumbnail($temp_name, $new): void
    {
        $target_file = SB_DYNAMIC_PATH . '/custom_thumbnails/' . $new . '.png';

        UnorganizedFunctions::processCustomThumbnail($temp_name, $target_file);

        unlink($temp_name);
    }

    public function deleteSubmission($data): void
    {
        if ($data["post_type"] == 0) {
            if ($this->chazizInstance) {
                $streamApi = new StreamAPI(
                    apiKey: $this->bunnyCDNSettings["streamApi"],
                    client: $this->bunnyClient,
                );

                $streamApi->deleteVideo(
                    libraryId: $this->bunnyCDNSettings["streamLibrary"],
                    videoId: $data["videofile"],
                );
            } else {
                unlink(SB_ROOT_PATH . $data["videofile"]);
            }
        } else {
            unlink(SB_ROOT_PATH . $data["videofile"]);
        }
    }
}