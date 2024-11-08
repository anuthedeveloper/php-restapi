<?php

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

if (!function_exists('jsonResponse')) {
    /**
     * Print in json object format
     */
    function jsonResponse(array $data, int $statusCode = 200): void {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
