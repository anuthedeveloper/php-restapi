<?php
// config/JWT.php
namespace Config;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JWT {
    private static string $secretKey = "your_secret_key";

    public static function generateToken($payload): string 
    {
        return FirebaseJWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function verifyToken($token): object 
    {
        return FirebaseJWT::decode($token, new Key(self::$secretKey, 'HS256'));
    }
}
