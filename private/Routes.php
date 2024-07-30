<?php
/*
 * SPDX-License-Identifier: AGPL-3.0-only
 */

/**
 * OpenSBFramework Routing
 *
 * Each URI points to it's specfic class and that classes method.
 * for example, [ExampleController, "example_func"] would point to "example_func" in the ExampleController class
 *
 * All controllers are in ``src/Controller``. Dynamic routing still hasn't been implemented because i'm a lazy bitch
 */

use OpenSB\Framework\Router;

use OpenSB\Controllers\IndexController;
use OpenSB\Controllers\MiscController;
use OpenSB\Controllers\ViewController;
use OpenSB\Controllers\AuthController;
use OpenSB\Controllers\UploadController;
use OpenSB\Controllers\ProfileController;
use OpenSB\Controllers\SBMigrateController;
use OpenSB\Controllers\BrowseController;
use OpenSB\Controllers\API\PlayerController;

$router = new Router();

$router->GET("/", [IndexController::class, "index"]);

$router->GET("/help", [MiscController::class, "help"]);
$router->GET("/guidelines", [MiscController::class, "guidelines"]);
$router->GET("/privacy", [MiscController::class, "privacy"]);

$router->GET("/browse", [BrowseController::class, "browse"]);
$router->GET("/view", [ViewController::class, "view"]);
$router->GET("/profile", [ProfileController::class, "profile"]);

$router->GET("/signin", [AuthController::class, "signin"])->useMiddleware("guest");
$router->POST("/signin", [AuthController::class, "signin_post"])->useMiddleware("guest");
$router->GET("/signup", [AuthController::class, "signup"])->useMiddleware("guest");
$router->POST("/signup", [AuthController::class, "signup_post"])->useMiddleware("guest");
$router->GET("/signout", [AuthController::class, "signout"])->useMiddleware("loggedIn");

$router->GET("/upload", [UploadController::class, "upload"])->useMiddleware("loggedIn");
$router->POST("/upload", [UploadController::class, "upload_post"])->useMiddleware("loggedIn");

// TODO: remove this.
$router->GET("/api/player/get_video", [PlayerController::class, "getVideo"]);

return $router;
