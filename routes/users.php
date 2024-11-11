<?php
// v1/users.php

use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\UserController;

$userController = new UserController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

AuthMiddleware::handle(); // Check token before accessing user data
