<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class ViewController extends Controller {
    public function view($id) {
        // placeholder, this should redirect to the homepage while showing an error banner.
        if (!isset($id)) {
            throw new \Exception("Missing upload id.");
        }

        $submission = $this->db->execute("SELECT * FROM videos where video_id = ?", [$id], true);

        // ditto.
        if (!$submission) {
            throw new \Exception("This upload does not exist.");
        }

        echo(json_encode([
            'data' => [
                "submission" => $submission,
            ],
        ]));
    }
}
