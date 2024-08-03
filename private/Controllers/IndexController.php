<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class IndexController extends Controller {
    public function index() {
        $uploads = $this->db->execute("SELECT * FROM videos ORDER BY time DESC LIMIT 12");

        return $this->frontend->render("index", [
            'data' => [
                "submissions" => $uploads,
            ],
        ]);
    }
}
