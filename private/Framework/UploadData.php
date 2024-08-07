<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

// NOTE: this is not the same thing as the previous implementation of uploaddata

class UploadData implements Data
{
    private Database $database;
    private $data;

    public function __construct(Database $database, $data)
    {
        $this->database = $database;
        $this->data = $this->database->execute("SELECT * FROM videos where video_id = ?", [$data], true);
    }

    public function getData(): array
    {
        return [
            "id" => $this->data["video_id"],
            "title" => $this->data["title"],
            "description" => $this->data["description"],
            "published" => $this->data["time"],
            "published_originally" => $this->data["original_time"],
            "original_site" => $this->data["original_site"],
            "type" => $this->data["post_type"],
            "content_rating" => $this->data["rating"],
            "views" => $this->data["views"],
            //"flags" => $bools,
            "author" => [
                "id" => $this->data["author"],
                //"info" => $userData->getUserArray(),
            ],
        ];
    }

    public function modifyData($data): bool
    {
        // TODO: Implement modifyData() method.
        return false;
    }
}