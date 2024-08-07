<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class BrowseController extends Controller {
    public function browse() {
        // TODO: PAGINATION

        $category = $_GET["category"] ?? null;

        if ($category == null) {
            throw new \Exception("Invalid catagory");
        }

        // todo: validation

        $uploads = $this->db->execute("SELECT * FROM uploads where type = ? LIMIT 12", [$category]);

        return $this->frontend->render("browse", [
            'uploads' => $uploads,
            'category' => $category,
        ]);
    }
}
