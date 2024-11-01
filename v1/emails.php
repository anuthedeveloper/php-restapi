<?php
// v1/emails.php

require_once __DIR__ . '/../controllers/EmailController.php';

$emailController = new EmailController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($requestMethod) {
    case 'POST':
        if (!empty($input['to']) && !empty($input['subject']) && !empty($input['message'])) {
            // Using PHPMailer
            $emailController->sendEmailWithPHPMailer($input['to'], $input['subject'], $input['message']);
            
            // Or use the simpler mail() function
            // $emailController->sendSimpleEmail($input['to'], $input['subject'], $input['message']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Required fields: to, subject, message']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
