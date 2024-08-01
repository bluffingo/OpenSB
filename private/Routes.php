<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

/**
 * OpenSB Framework Routing
 *
 * Each URI points to its specific class and that classes method.
 * for example, [ExampleController, "example_func"] would point to "example_func" in the ExampleController class
 *
 * All controllers are in ``src/Controller``. Dynamic routing still hasn't been implemented because i'm a lazy bitch
 */

// TODO:
// * redirect urls with ".php" to urls without ".php" like in the old code
// * make old urls redirect to current urls again (https://squarebracket.pw/user?name=Chaziz to https://squarebracket.pw/user/Chaziz)
// * since we have the squarebracket.me domain again (sorry, ratelimited!), make old poktube links redirect to the current-day urls.

use OpenSB\Framework\Router;

use OpenSB\Controllers\IndexController;
use OpenSB\Controllers\MiscController;
use OpenSB\Controllers\ViewController;
use OpenSB\Controllers\AuthController;
use OpenSB\Controllers\UploadController;
use OpenSB\Controllers\ProfileController;
use OpenSB\Controllers\BrowseController;

$router = new Router();

$router->GET("/", [IndexController::class, "index"]);

$router->GET("/help", [MiscController::class, "help"]);
$router->GET("/guidelines", [MiscController::class, "guidelines"]);
$router->GET("/privacy", [MiscController::class, "privacy"]);

$router->GET("/browse", [BrowseController::class, "browse"]);
$router->GET("/view/{id}", [ViewController::class, "view"]);
$router->GET("/profile", [ProfileController::class, "profile"]);

$router->GET("/login", [AuthController::class, "signin"])->useMiddleware("guest");
$router->POST("/login", [AuthController::class, "signin_post"])->useMiddleware("guest");
$router->GET("/register", [AuthController::class, "signup"])->useMiddleware("guest");
$router->POST("/register", [AuthController::class, "signup_post"])->useMiddleware("guest");
$router->GET("/logout", [AuthController::class, "signout"])->useMiddleware("loggedIn");

$router->GET("/upload", [UploadController::class, "upload"])->useMiddleware("loggedIn");
$router->POST("/upload", [UploadController::class, "upload_post"])->useMiddleware("loggedIn");

return $router;
