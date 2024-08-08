<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Framework;

class UserData implements Data
{
    private Database $database;
    private $data;

    public function __construct(Database $database, $data)
    {
        $this->database = $database;
        $this->data = $this->database->execute("SELECT u.* FROM users u WHERE u.id = ?", [$data], true);
    }

    public function getData(): array
    {
        return [
            "username" => $this->data["name"],
            "displayname" => $this->data["title"],
            "color" => $this->data["customcolor"],
            "followers" => 123456789, // Placeholder
            "joined" => $this->data["joined"],
            "connected" => $this->data["lastview"],
            "customcolor" => $this->data["customcolor"],
        ];
    }

    // modifydata shouldnt be used for userdata.
    public function modifyData($data): bool
    {
        trigger_error("Attempted to call modifyData on UserData (this is stupid)", E_USER_ERROR);
    }
}