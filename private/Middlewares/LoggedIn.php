<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Middlewares;

use OpenSB\App;
use OpenSB\Framework\Authentication;
use OpenSB\Framework\Middleware;

class LoggedIn extends Middleware {
    public function handle($uri, $method) {
        $authService = App::container()->get(Authentication::class);

        if (!$authService->isLoggedIn()) {
            header("Location: /signin");
            return;
        }
    }
}
