<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Controllers;

use OpenSB\Framework\Controller;

class MiscController extends Controller {
    public function privacy() {
        return $this->frontend->render("_markdown", [
            'page' => 'privacy.md',
        ]);
    }

    public function help() {
        return $this->frontend->render("_markdown", [
            'page' => 'help.md',
        ]);
    }

    public function guidelines() {
        return $this->frontend->render("_markdown", [
            'page' => 'guidelines.md',
        ]);
    }
}
