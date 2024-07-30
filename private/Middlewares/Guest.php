<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB\Middlewares;

use OpenSB\App;
use OpenSB\Framework\Auth;
use OpenSB\Framework\Middleware;

class Guest extends Middleware {
    public function handle($uri, $method) {
        $authService = App::container()->get(Auth::class);

        if ($authService->isLoggedIn()) {
            header("Location: /");
        }
    }
}
