<?php

namespace Middleware;

class ValidationMiddleware
{
    public static function handle(array $input)
    {
        foreach ($input as $key => $value) {
            if (empty($value)) {
                http_response_code(400);
                echo json_encode(['error' => "Field '$key' is required."]);
                exit;
            }
            $input[$key] = htmlspecialchars(strip_tags($value));
        }
        return $input;
    }
}
