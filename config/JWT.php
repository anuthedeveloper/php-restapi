<?php
// config/JWT.php
namespace Config;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JWT {
    private static string $secretKey = $_ENV['JWT_SECRET'];

    public static function generateToken($userId): string 
    {
        $payload = [
            "iss" => "yourdomain.com",
            "aud" => "yourdomain.com",
            "iat" => time(),
            "exp" => time() + (60 * 60), // Token expires in 1 hour
            "userId" => $userId
        ];
        return FirebaseJWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function verifyToken($token): object 
    {
        return FirebaseJWT::decode($token, new Key(self::$secretKey, 'HS256'));
    }
}
