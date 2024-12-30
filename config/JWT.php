<?php
// config/JWT.php
namespace Config;

use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;

class JWT {
    private static ?string $secretKey = null;

    public static function setSecretKey(): void
    {
        if (self::$secretKey === null ) {
            self::$secretKey = getenv('JWT_SECRET') ?: '';
        }
    }

    public static function generateToken($userId): string 
    {
        // Ensure secret key is set
        self::setSecretKey();

        $payload = [
            "iss" => "yourdomain.com",
            "aud" => "yourdomain.com",
            "iat" => time(),
            "exp" => time() + (60 * 60), // Token expires in 1 hour
            "user" => $userId
        ];
        return FirebaseJWT::encode($payload, self::$secretKey, 'HS256');
    }

    public static function verifyToken($token): object 
    {
        // Ensure secret key is set
        self::setSecretKey();

        return FirebaseJWT::decode($token, new Key(self::$secretKey, 'HS256'));
    }
}
