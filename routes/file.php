<?php
// v1/files.php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers\FileController;

$fileController = new FileController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
