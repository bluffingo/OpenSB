<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

use OpenSB\Framework\UploadDataArray;

class IndexController extends Controller {
    public function index() {
        $random_uploads = new UploadDataArray($this->db, "RAND()", 24);
        $recent_uploads = new UploadDataArray($this->db, "v.time DESC", 12);

        return $this->frontend->render("index", [
            'data' => [
                "submissions" => $random_uploads->getDataArray(),
                'submissions_new' => $recent_uploads->getDataArray(),
            ],
        ]);
    }
}
