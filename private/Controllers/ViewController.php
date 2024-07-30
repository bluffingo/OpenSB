<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class ViewController extends Controller {
    public function view() {
        $id = $_GET["id"];

        // placeholder, this should redirect to the homepage while showing an error banner,
        // akin to vidlii and opensb1.
        if (!isset($id)) {
            throw new \Exception("Missing upload id.");
        }

        $submission = $this->db->execute("SELECT * FROM uploads where display_id = ?", [$id], true);

        // ditto.
        if (!$submission) {
            throw new \Exception("This upload does not exist.");
        }

        $this->frontend->render("view", [
            'submission' => $submission,
        ]);
    }
}
