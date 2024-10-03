<?php

namespace OpenSB\class;

class Router {
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    private $core_classes;

    public function __construct(CoreClasses $core_classes) {
        $this->core_classes = $core_classes;
    }

    public function register($method, $path, $className) {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $className;
    }

    public function resolve($requestUri) {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($path, $this->routes[$method])) {
            $className = $this->routes[$method][$path];

            if (class_exists($className)) {
                $page = new $className();
                return $page->render($_REQUEST);
            } else {
                return "Page class '$className' not found.";
            }
        }

        return $this->render404();
    }

    private function render404() {
        http_response_code(404);
        return "404";
    }
}