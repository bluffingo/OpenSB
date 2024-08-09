<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class BrowseController extends Controller {
    public function browse() {
        // TODO: pagination

        $uploads = $this->db->execute("SELECT * FROM videos LIMIT 12");

        return $this->frontend->render("browse", [
            'uploads' => $uploads,
        ]);
    }
}
