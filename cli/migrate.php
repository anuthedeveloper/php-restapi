#!/usr/bin/env php
<?php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use Config\Database;

$db = Database::getConnection();  // Get the PDO connection

/**
 * Apply all migrations in the migrations directory.
 *
 * @param PDO $db
 */
function migrateUp(PDO $db) {
    foreach (glob(__DIR__ . '/../migrations/*.php') as $file) {
        require_once $file;
        $fileName = basename($file, '.php');

        // Call the `up` function defined in the migration file
        if (function_exists('up')) {
            echo "Applying migration: $fileName...\n";
            up($db);
        }
    }
    echo "All migrations applied successfully.\n";
}

/**
 * Roll back all migrations in the migrations directory.
 *
 * @param PDO $db
 */
function migrateDown(PDO $db) {
    $files = glob(__DIR__ . '/../migrations/*.php');
    $files = array_reverse($files);  // Rollback in reverse order

    foreach ($files as $file) {
        require_once $file;
        $fileName = basename($file, '.php');

        // Call the `down` function defined in the migration file
        if (function_exists('down')) {
            echo "Rolling back migration: $fileName...\n";
            down($db);
        }
    }
    echo "All migrations rolled back successfully.\n";
}

// Parse command-line arguments
$command = $argv[1] ?? null;
if ($command === 'up') {
    migrateUp($db);
} elseif ($command === 'down') {
    migrateDown($db);
} else {
    echo "Usage: migrate.php [up|down]\n";
    exit(1);
}
