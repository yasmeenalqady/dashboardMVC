<?php 
namespace App\Core;

use App\Core\Database;

class Route {
    protected static array $routes = [];

    // تسجيل راوت عام (GET - POST - PUT - DELETE)
    protected static function add(string $method, string $uri, array $action) {
        self::$routes[$method][$uri] = $action;
    }

    public static function get(string $uri, array $action) {
        self::add('GET', $uri, $action);
    }

    public static function post(string $uri, array $action) {
        self::add('POST', $uri, $action);
    }

    public static function put(string $uri, array $action) {
        self::add('PUT', $uri, $action);
    }

    public static function delete(string $uri, array $action) {
        self::add('DELETE', $uri, $action);
    }

    // resource routes
    public static function resource(string $name, string $controller) {
        self::get("/$name", [$controller, 'index']);
        self::get("/$name/create", [$controller, 'create']);
        self::post("/$name", [$controller, 'store']);
        self::get("/$name/deleted", [$controller, 'deleted']);
        self::post("/$name/{id}/destroyPermanent", [$controller, 'destroyPermanent']);
        self::get("/$name/{id}", [$controller, 'show']);
        self::get("/$name/{id}/edit", [$controller, 'edit']);
        self::put("/$name/{id}", [$controller, 'update']);
        self::delete("/$name/{id}", [$controller, 'destroy']);
        self::post("/$name/{id}/restore", [$controller, 'restore']);
    }

    // تنفيذ الراوت
    public static function dispatch(string $method, string $uri) {
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }

        $database = new Database();
        $db = $database->connect();

        $uri = parse_url($uri, PHP_URL_PATH);

        // إزالة base path
        $basePath = '/MVC/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $uri = '/' . trim($uri, '/');

        $routes = self::$routes[$method] ?? [];

        foreach ($routes as $pattern => $action) {
            preg_match_all('/\{([a-z_]+)\}/', $pattern, $paramNames);
            $regex = '#^' . preg_replace('/\{[a-z_]+\}/', '([^/]+)', $pattern) . '/?$#';

            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches);
                $params = [];

                if (!empty($paramNames[1])) {
                    foreach ($paramNames[1] as $index => $name) {
                        $params[$name] = $matches[$index] ?? null;
                    }
                }

                [$controllerClass, $methodName] = $action;

                if (!class_exists($controllerClass)) {
                    die("<h1>Controller $controllerClass not found</h1>");
                }

                $controller = new $controllerClass($db);

                if (!method_exists($controller, $methodName)) {
                    die("<h1>Method $methodName not found in $controllerClass</h1>");
                }

                return call_user_func_array([$controller, $methodName], $params);
            }
        }

        die("<h1>404 - Page Not Found</h1>");
    }
}
