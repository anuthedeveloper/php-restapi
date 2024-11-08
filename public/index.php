<?php

require_once __DIR__ . '/../bootstrap/bootstrap.php';

use Controllers\UserController;
use Middleware\AuthMiddleware;
use Helpers\Response;

// Simple router based on the request URI and HTTP method
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Include the routes and handle the request
// require_once __DIR__ . '/../routes/api.php';
// Define routes
$routes = [
    'GET' => [
        '/users' => [UserController::class, 'index'],
        '/users/{id}' => [UserController::class, 'show']
    ],
    'POST' => [
        '/users' => [UserController::class, 'store'],
        '/login' => [UserController::class, 'login']
    ],
    'PUT' => [
        '/users/{id}' => [UserController::class, 'update']
    ],
    'DELETE' => [
        '/users/{id}' => [UserController::class, 'destroy']
    ]
];

// Route matching function with support for dynamic parameters
function matchRoute($routes, $method, $uri)
{
    if (!isset($routes[$method])) return null;

    foreach ($routes[$method] as $route => $handler) {
        // Replace route parameters with regex to capture values
        $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
        $routePattern = str_replace('/', '\/', $routePattern);
        $routePattern = "/^" . $routePattern . "$/";

        if (preg_match($routePattern, $uri, $matches)) {
            array_shift($matches); // Remove full match from matches
            return ['handler' => $handler, 'params' => $matches];
        }
    }

    return null;
}

// Match the current request to a route
$route = matchRoute($routes, $requestMethod, $requestUri);

if ($route) {
    list($controller, $method) = $route['handler'];

    // Check if the route requires authorization
    if (in_array($method, ['store', 'update', 'destroy']) && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
        Response::json(['error' => 'Unauthorized'], 401);
        exit;
    }

    // Check and validate token for secured routes
    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        AuthMiddleware::checkAuthorization();
    }

    // Call the controller method with dynamic parameters
    (new $controller)->$method(...$route['params']);
} else {
    // Route not found
    Response::json(['error' => 'Route not found'], 404);
}
