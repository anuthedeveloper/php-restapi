<?php
// middleware/AuthMiddleware.php
namespace App\Http\Middleware;

use Config\JWT;
use Config\Session;
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

    private static function checkAuthSession()
    {
        if (!Session::has('user')) {
            response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public static function handle(Request $request, callable $next) 
    {
        $token = self::checkToken();
        try {
            if (!$decoded = JWT::verifyToken($token)) {
                throw new Exception("Unauthorized! Invalid token", 1);
            }
            self::checkAuthSession();
            
            return $decoded->userId;
        } catch (Exception $e) {
            response()->json(['error' => $e->getMessage()], 401);
        }
        return $next($request);
    }

}
