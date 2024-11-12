<?php
namespace App\Http;

use ReflectionMethod;

class Router
{
    // Define routes property, if required
    protected $routes = [];

    // Register a route with a method, route pattern, and controller-action pair
    public function add(string $method, string $route, array $controllerAction)
    {
        // Normalize the HTTP method to uppercase (GET, POST, etc.)
        $this->routes[strtoupper($method)][$route] = $controllerAction;
    }

    // Find the controller and action for a given request method and route
    public function resolve(string $method, string $route)
    {
        // $this->routes[strtoupper($method)][$route];
        if (!isset($this->routes[strtoupper($method)])) {
            return null;
        }

        foreach ($this->routes[$method] as $routePattern => $controllerAction) {
            if ($this->matchRoute($routePattern, $route, $params)) {
                return ['controllerAction' => $controllerAction, 'params' => $params];
            }
        }

        return null;
    }
    
    // Match the current request to a route
    public function handleRequest($requestUri, $requestMethod)
    {
        $requestMethod = strtoupper($requestMethod);
        $requestUri = preg_replace('/^\/api\/v1/', '', $requestUri);

        // Check if the method exists in the registered routes
        if (isset($this->routes[$requestMethod])) {
            foreach ($this->routes[$requestMethod] as $route => $controllerAction) {
                // Match the route with the request URI
                if ($this->matchRoute($route, $requestUri, $params)) {
                    list($controllerClass, $action) = $controllerAction;

                    // Instantiate the controller and call the action method
                    $controller = new $controllerClass();

                    // Reflection to call the method dynamically
                    $method = new ReflectionMethod($controller, $action);
                    return $method->invokeArgs($controller, $params);
                }
            }
        }

        // Return 404 if no match is found
        return response()->json(['error' => 'Not Found'], 404, true);
    }

    // Match a route to the request URI
    private function matchRoute(string $routePattern, string $requestUri, &$params): bool
    {
        // Convert route and URI to regex
        $routeRegex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePattern);
        $routeRegex = '#^' . $routeRegex . '$#';

        if (preg_match($routeRegex, $requestUri, $matches)) {
            // If match is found, extract the parameters from the route
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }

        return false;
    }
}
