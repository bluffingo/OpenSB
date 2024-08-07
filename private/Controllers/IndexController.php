<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

use OpenSB\Framework\UploadArray;

class IndexController extends Controller {
    public function index() {
        $upload_array = new UploadArray($this->db);

        $random_uploads = $upload_array->query("RAND()", 24);
        $recent_uploads = $upload_array->query("v.time DESC", 12);

        return $this->frontend->render("index", [
            'data' => [
                "submissions" => $random_uploads,
                'submissions_new' => $recent_uploads,
            ],
        ]);
    }
}
