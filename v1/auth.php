<?php
// v1/auth.php
require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\AuthController;

$authController = new AuthController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $authController->login($data);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
