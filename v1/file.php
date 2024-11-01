<?php
// v1/files.php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\FileController;

$fileController = new FileController();
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        if (isset($_FILES['file'])) {
            $fileController->uploadFile($_FILES['file']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'File is required']);
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $fileController->getFile($_GET['id']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'File ID is required']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
