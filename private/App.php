<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace OpenSB;

use OpenSB\Framework\Container;
use OpenSB\Framework\Router;
use OpenSB\Helpers\Profiler;

class App {
    public const MIDDLEWARES = [
        "guest" => \OpenSB\Middlewares\Guest::class,
        "loggedIn" => \OpenSB\Middlewares\LoggedIn::class,
    ];
    protected static Container $container;
    protected static array $config;

    public static function resolveMiddleware($key, $uri, $method) {
        if (!$key) {
            return;
        }

        $middleware = static::MIDDLEWARES[$key] ?? false;

        if (!$middleware) {
            throw new \Exception("No matching middleware found");
        }

        (new $middleware)->handle($uri, $method);
    }

    public static function container() {
        if (static::$container === null) {
            throw new \Exception("You haven't set the container!");
        };

        return static::$container;
    }

    public static function config() {
        if (static::$config === null) {
            throw new \Exception("You haven't set the app config!");
        }

        return static::$config;
    }

    public static function run(Container $container, Router $router, array $config) {
        try {
            static::$container = $container;
            static::$config = $config;

            $router->run(parse_url($_SERVER["REQUEST_URI"])["path"], $_SERVER['REQUEST_METHOD']);
        } catch (\Exception $error) {
            die('<pre>OpenSB: Something went very wrong. Error:</pre> <pre>'. $error->getMessage() . '</pre>');
        }

        self::cleanup();
    }

    private static function cleanup() {
        unset($_SESSION["__flash"]);

        if (self::$config["mode"] == "DEV") {
            Profiler::getInfo();
        }
    }
}
