<?php
// v1/auth.php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers\AuthController;

$authController = new AuthController();
$requestMethod = $_SERVER['REQUEST_METHOD'];