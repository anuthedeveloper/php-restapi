<?php
// middleware/AuthMiddleware.php
namespace Middleware;

use Config\JWT;
use Exception;

class AuthMiddleware {
    public static function verifyToken() 
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            return JWT::verifyToken($token);
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized: Invalid token']);
            exit();
        }
    }
}
