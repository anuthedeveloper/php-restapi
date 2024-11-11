<?php
// v1/emails.php

require_once __DIR__ . '/../controllers/EmailController.php';

$emailController = new EmailController();
$requestMethod = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
