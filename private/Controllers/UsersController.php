<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class UsersController extends Controller {
    public function users() {
        // TODO: pagination

        $users = $this->db->execute("SELECT * FROM users LIMIT 12");

        return $this->frontend->render("users", [
            'users' => $users,
        ]);
    }
}
