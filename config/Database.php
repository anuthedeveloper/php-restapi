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

    public static function initialize(): void
    {
        // Load credentials from environment variables
        // $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_CHARSET']}";
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASSWORD'];

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
    }

    public static function getConnection(): ?PDO 
    {
        return self::$connection;
    }

    public function __destruct()
    {
        self::$connection = null;
    }
}
