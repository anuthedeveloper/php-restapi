<?php
// config/Database.php
namespace Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;

    private function __construct()
    {
        // Make the constructor private to prevent instantiation
    }

    public static function getConnection(): PDO 
    {
        // Load credentials from environment variables
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        try {
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            if (self::$connection === null) {
                self::$connection = new PDO($dsn, $user, $pass, $options);
            }
        } catch (PDOException $e) {
              // Handle connection error
              error_log('Database Connection Error: ' . $e->getMessage());
              throw new \Exception("Database connection failed.");
        }

        return self::$connection;
    }

    public function __destruct()
    {
        self::$connection = null;
    }
}
