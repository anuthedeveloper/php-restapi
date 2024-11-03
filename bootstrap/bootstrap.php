<?php 
// bootstrap/bootstrap.php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from the .env file in the project root
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Automatically include all helper files
$helperFiles = glob(__DIR__ . '/helpers/*.php');
foreach ($helperFiles as $file) {
    require_once $file;
}