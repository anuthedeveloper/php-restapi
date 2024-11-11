<?php
// middleware/AuthMiddleware.php
namespace App\Http\Middleware;

use Config\JWT;
use Exception;

class AuthMiddleware {

    public static function handle() 
    {
        $token = self::checkToken();
        try {
            $decoded = JWT::verifyToken($token);
            return $decoded->userId;
        } catch (Exception $e) {
            response()->json(['error' => 'Unauthorized: Invalid token'], 401);
        }
    }
    
    private static function checkToken() 
    {
        $headers = getallheaders();
        $authorization = $headers['Authorization'] ?? '';
        $httpAuthorization = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? $_SERVER['HTTP_AUTHORIZATION'];

        $authHeader = $authorization ?? $httpAuthorization;
        if (!isset($authHeader)) {
            response()->json(['error' => 'Unauthorized'], 401);
        }

        return str_replace('Bearer ', '', $authHeader);
    }

}
