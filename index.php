<?php
// Load environment variables and any required bootstrap code
require_once __DIR__ . '/bootstrap/bootstrap.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

switch ($requestPath) {
    case '/api/v1/auth':
        require 'v1/auth.php';
        break;
    case '/api/v1/users':
        require 'v1/users.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint not found']);
}
