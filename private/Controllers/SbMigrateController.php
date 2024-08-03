<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class SBMigrateController extends Controller {
    public function migrate() {
        return $this->frontend->render("migrate");
    }

    public function migrate_post() {
        // placeholder i swear
        $username = (isset($_POST['field_migrate_username']) ? $_POST['field_migrate_username'] : null);
        $password = (isset($_POST['field_migrate_password']) ? $_POST['field_migrate_password'] : null);

        if (empty($username) || empty($password)) {
            return [
                "success" => false,
                "error" => "Invalid username / password."
            ];
        }

        $user = $this->sbdb->execute("SELECT * FROM users WHERE name = ?", [$username], true);

        if (!$user) {
            return [
                "success" => false,
                "error" => "There is no account with that name."
            ];
        }

        $verify = password_verify($password, $user['password']);
        if ($verify) {
            print_r($user);
        } else {
            return [
                "success" => false,
                "error" => "Invalid username / password."
            ];
        }

    }
}
