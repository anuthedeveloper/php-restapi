<?php
// v1/files.php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Controllers\FileController;

$fileController = new FileController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
