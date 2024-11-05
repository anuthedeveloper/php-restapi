<?php
// ./cli/run_migrations.php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use Config\Database;

$db = Database::getConnection();  // Get the PDO connection

$command = $argv[1] ?? null;

$migrationFiles = glob(__DIR__ . '/migrations/*.php');
usort($migrationFiles, fn($a, $b) => strcmp($a, $b)); // Sort files by name

foreach ($migrationFiles as $file) {
    require_once $file;
    $className = basename($file, '.php');
    
    if (class_exists($className)) {
        $migration = new $className();
        
        if ($command === 'migrate') {
            $migration::up();
        } elseif ($command === 'rollback') {
            $migration::down();
        } else {
            echo "Invalid command. Use 'migrate' or 'rollback'.\n";
            exit(1);
        }
    } else {
        echo "Migration class $className not found in file $file.\n";
    }
}
