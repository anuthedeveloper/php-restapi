<?php
namespace App\Http;

class Route extends Router
{
    // Store the registered routes
    private static $routes = [];

    /**
     * Register a route with a method and controller-action pair.
     *
     * @param string $method       HTTP Method (GET, POST, etc.)
     * @param string $route        Route path
     * @param array  $controllerAction Controller and method
     */
    private static function addRoute(string $method, string $route, array $controllerAction, array $middleware = [])
    {
        self::$routes[strtoupper($method)][$route] = [
            'controllerAction' => $controllerAction, 
            'middleware' => $middleware
        ];
    }

    /**
     * Define GET route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function get(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('GET', $route, $controllerAction, $middleware);
    }

    /**
     * Define POST route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function post(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('POST', $route, $controllerAction, $middleware);
    }

    /**
     * Define PUT route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function put(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('PUT', $route, $controllerAction, $middleware);
    }

    /**
     * Define PATCH route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function patch(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('PATCH', $route, $controllerAction, $middleware);
    }

    /**
     * Define DELETE route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function delete(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('DELETE', $route, $controllerAction, $middleware);
    }

    /**
     * Define OPTIONS route
     *
     * @param string $route           Route path
     * @param array  $controllerAction Controller and method
     */
    public static function options(string $route, array $controllerAction, array $middleware = [])
    {
        self::addRoute('OPTIONS', $route, $controllerAction, $middleware);
    }

    /**
     * Handle the incoming request based on the URI and HTTP method.
     */
    public static function handle()
    {
        $request = Request::capture();
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Resolve the route using the Router class
        $routeInfo = self::resolveRoute($requestMethod, $requestUri);
        if ($routeInfo) {
            list($controllerClass, $action) = $routeInfo['controllerAction'];
            
            // Extract the middleware if set and apply to handle it
            if ( isset($routeInfo['middleware']) ) {
                foreach ($routeInfo['middleware'] as $middlewareClass) {
                    (new $middlewareClass())->handle($request);
                }
            }
            
            try {
                // Instantiate the controller and call the action
                $controllerInstance = new $controllerClass();
                // $response = $controllerInstance->$action($request);
                $response = call_user_func_array([$controllerInstance, $action], array_merge([$request], $routeInfo['params']));
                echo $response;
            } catch (\App\Exceptions\ValidationException $e) {
                response()->json(['errors' => $e->getErrors()], 422);
            } catch (\Exception $e) {
                response()->json(['error' => 'Internal Server Error'], 500);
            }
        } else {
            response()->json(['error' => 'Not Found'], 404);  // If no route is found, return 404
        }
    }

    /**
     * Resolve the route using the Router class.
     *
     * @param string $method   HTTP method (GET, POST, etc.)
     * @param string $route    Request URI
     * @return array|null      Controller and action if found, null if not
     */
    private static function resolveRoute(string $method, string $route)
    {
        // Make sure the Router class has been initialized
        $router = new Router();
       
        // Add the routes
        foreach (self::$routes as $methodKey => $routesArr) {
            foreach ($routesArr as $routePattern => $routeInfo) {
                $router->add($methodKey, $routePattern, $routeInfo);
            }
        }
     
        // Resolve the controller and action based on method and route
        return $router->resolve($method, $route);
    }
}
