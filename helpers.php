<?php

use App\Helpers\Response;

if (!function_exists('response')) {
    function response() {
        return new class {
            public function json(array $data, int $status = 200, bool $return = false) {
                Response::json($data, $status, $return);
                exit;
            }

            public function file(string $filePath, array $headers = []) {
                Response::file($filePath, $headers);
                exit;
            }
        };
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input to prevent XSS attacks
     */
    function sanitize_input(string $input): string {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array
     */
    function array_flatten(array $array): array {
        $result = [];
        array_walk_recursive($array, function ($value) use (&$result) {
            $result[] = $value;
        });
        return $result;
    }
}

if (!function_exists('matchRoute')) {
    /**
     * Route matching function with support for dynamic parameters
     */ 
    function matchRoute(array $routes, string $method, string $uri): ?array
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
}

if (!function_exists('handleException')) {
    /**
     * Send a JSON response with the erorr code.
     */
    function handleException(\Exception $e)
    {
        $statusCode = $e->getCode() ?: 500;
        Response::json(['error' => $e->getMessage(), $statusCode]);
    }
}

if (!function_exists('validateInput')) {
    /**
     * Determine whether a variable is empty
     */
    function validateInput(array $data, array $requiredFields) 
    {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = "Missing field: $field";
            }
        }
        return $errors;
    }
}

if (!function_exists('sanitize')) {
    /**
     * Convert special characters to HTML entities
     */
    function sanitize(mixed $input): string
    {
        return htmlspecialchars(strip_tags($input));
    }
}

if (!function_exists('validateEmail')) {
    /**
     * Filters a variable with a specified filter
     */
    function validateEmail(string $email): mixed
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

if (!function_exists('hashPassword')) {
     /**
     * Hash Password Algo
     */
    function hashPassword(string $password): string
    {
        // CRYPT_BLOWFISH 
        return password_hash($password, PASSWORD_BCRYPT, ["option" => 8]); 
    }
}

if (!function_exists('sanitize_sensitive_data')) {
    /**
     * Removes sensitive credentials by replacing them with a placeholder.
     *
     * @param array|string $data Data containing sensitive information.
     * @param string $placeholder The value to replace sensitive data with.
     * @return array|string The sanitized data.
     */
    function sanitize_sensitive_data(array|string $data, string $placeholder = '***'): array|string
    {
        if (is_array($data)) {
            return array_map(fn($value) => $placeholder, $data);
        }

        return $placeholder;
    }
}
