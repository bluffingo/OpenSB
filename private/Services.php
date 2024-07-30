<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

/**
 * Dependancy Injection services to use in the app.
 *
 * Put shit here you want to use in
 */

use OpenSB\Framework\Container;

use OpenSB\Framework\DB;
use OpenSB\Framework\Auth;
use OpenSB\Framework\Frontend;

$container = new Container();

$container->set(DB::class, fn () => new DB($config["mysql"]));
$container->set(Auth::class, fn () => new Auth((isset($_SESSION["token"]) ? $_SESSION["token"] : null)));
$container->set(Frontend::class, fn () => new Frontend());

return $container;
