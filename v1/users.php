<?php
// v1/users.php

use Middleware\AuthMiddleware;
use Controllers\UserController;

$userController = new UserController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

AuthMiddleware::checkAuthorization(); // Check token before accessing user data

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
        response()->json(['error' => 'Method Not Allowed'], 405);
}
