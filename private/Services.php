<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

// FUCK
global $config;

/**
 * Dependancy Injection services to use in the app.
 *
 * Put shit here you want to use in
 */

use OpenSB\Framework\Container;

use OpenSB\Framework\Database;
use OpenSB\Framework\Authentication;
use OpenSB\Framework\Frontend;
use OpenSB\Framework\SiteConfig;

$container = new Container();

$container->set(Database::class, fn () => new Database($config["mysql"]));
$container->set(Authentication::class, fn () => new Authentication(($_SESSION["token"] ?? null)));
$container->set(Frontend::class, fn () => new Frontend());
$container->set(SiteConfig::class, fn () => new SiteConfig($config["site"]));

return $container;
