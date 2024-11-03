<?php
// v1/users.php

use Middleware\AuthMiddleware;
use Controllers\UserController;

$userController = new UserController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

AuthMiddleware::verifyToken(); // Check token before accessing user data

switch ($requestMethod) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $userController->createUser($data);
        break;
    case 'GET':
        if (isset($_GET['id'])) {
            $userController->getUser((int)$_GET['id']);
        } else {
            $userController->jsonResponse(User::all());
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
}
