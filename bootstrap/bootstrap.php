<?php 
// bootstrap/bootstrap.php

// Autoload dependencies using Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Config\Database;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Load environment variables from .env file if it exists
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();  // This allows the script to run even if .env file is missing

// Initialize error handling settings
error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_DEBUG') === 'true' ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../storage/logs/error.log');

// Set up logging using Monolog
$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../storage/logs/app.log', Logger::DEBUG));

// Exception and Error Handling
set_exception_handler(function ($e) use ($log) {
    $log->error($e->getMessage(), ['exception' => $e]);
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
});

set_error_handler(function ($level, $message, $file, $line) use ($log) {
    $log->error($message, ['level' => $level, 'file' => $file, 'line' => $line]);
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
});

// Initialize database connection (using Singleton pattern)
Database::initialize();

// Example: Define global constants (if needed)
define('BASE_PATH', __DIR__ . '/../');
define('STORAGE_PATH', BASE_PATH . 'storage/');

// Load any additional helpers or middleware if necessary
// require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';
