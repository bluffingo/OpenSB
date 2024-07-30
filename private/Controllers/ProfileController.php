<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class ProfileController extends Controller {
    public function profile() {
        $id = $_GET["user"];

        // placeholder, this should redirect to the homepage while showing an error banner,
        // akin to vidlii and opensb.
        if (!isset($id)) {
            throw new \Exception("Missing username.");
        }

        $profile = $this->db->execute("SELECT * FROM users where name = ?", [$id], true);

        // ditto.
        if (!$profile) {
            throw new \Exception("This user does not exist.");
        }

        $this->frontend->render("profile", [
            'profile' => $profile,
        ]);
    }
}
