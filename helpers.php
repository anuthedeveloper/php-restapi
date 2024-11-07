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
