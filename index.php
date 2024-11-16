<?php
// Load environment variables and any required bootstrap code
require_once __DIR__ . '/bootstrap/bootstrap.php';

use App\Http\Route;

// Load routes from routes/v1/api.php
require_once __DIR__ . '/routes/v1/api.php';

Route::handle();

// Simple router based on the request URI and HTTP method
// $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// $requestMethod = $_SERVER['REQUEST_METHOD'];

// // Remove base path '/api/v1' from the URL for routing purposes
// $requestUri = str_replace('/api/v1', '', $requestUri);

// // Include the routes and handle the request
// require_once __DIR__ . '/../routes/api.php';

// Match the current request to a route
// $route = matchRoute($routes, $requestMethod, $requestUri);

// if ($route) {
//     list($controller, $method) = $route['handler'];

//     // Check if the route requires authorization
//     if (in_array($method, ['store', 'update', 'destroy']) && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
//         Response::json(['error' => 'Unauthorized'], 401);
//         exit;
//     }

//     // Check and validate token for secured routes
//     if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
//         AuthMiddleware::checkAuthorization();
//     }

//     // Call the controller method with dynamic parameters
//     (new $controller)->$method(...$route['params']);
// } else {
//     // Route not found
//     Response::json(['error' => 'Route not found'], 404);
// }
