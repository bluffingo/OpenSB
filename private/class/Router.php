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

        foreach ($this->routes[$method] as $route => $className) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);

            if (preg_match("/^{$pattern}$/", $path, $matches)) {
                array_shift($matches);

                if (class_exists($className)) {
                    $page = new $className($this->core_classes);
                    return $page->render($_REQUEST, $matches);
                } else {
                    return "Page class '$className' not found.";
                }
            }
        }

        return $this->render404();
    }

    private function render404() {
        http_response_code(404);
        return "404";
    }
}