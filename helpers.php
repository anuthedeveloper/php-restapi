<?php

use Helpers\Response;

if (!function_exists('response')) {
    function response() {
        return new class {
            public function json(array $data, int $status = 200, array $headers = []) {
                Response::json($data, $status, $headers);
            }

            public function file(string $filePath, array $headers = []) {
                Response::file($filePath, $headers);
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

