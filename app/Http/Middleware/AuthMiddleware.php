<?php
// middleware/AuthMiddleware.php
namespace App\Http\Middleware;

use Config\JWT;
use App\Http\Request;
use Exception;

class AuthMiddleware {

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

    public static function handle(Request $request, callable $next) 
    {
        $token = self::checkToken();
        try {
            $decoded = JWT::verifyToken($token);
            return $decoded->userId;
        } catch (Exception $e) {
            response()->json(['error' => 'Unauthorized! Invalid token'], 401);
        }
        return $next($request);
    }

}
