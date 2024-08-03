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

use OpenSB\Framework\Database;
use OpenSB\Framework\Authentication;
use OpenSB\Framework\Frontend;

$container = new Container();

$container->set(Database::class, fn () => new Database($config["mysql"]));
$container->set(Authentication::class, fn () => new Authentication((isset($_SESSION["token"]) ? $_SESSION["token"] : null)));
$container->set(Frontend::class, fn () => new Frontend());

return $container;
