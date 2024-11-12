<?php
// ./cli/run_migrations.php
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Schemas\MigrationInterface;

$command = $argv[1] ?? null;

$migrationFiles = glob(__DIR__ . '/../migrations/*.php');
usort($migrationFiles, fn($a, $b) => strcmp($a, $b)); // Sort files by name

foreach ($migrationFiles as $file) {
    require_once $file;

    // Extract the class name from the file name by removing the timestamp and converting it to PascalCase
    $filename = basename($file, '.php');
    $className = convertFilenameToClassName($filename);

    if (class_exists($className)) {
        print "Running migration for: $filename\n";
        $migration = new $className();

        if (!$migration instanceof MigrationInterface) {
            print "Error: $className must implement MigrationInterface.\n";
            continue;
        }
                
        if ($command === 'migrate') {
            $migration::up();
        } elseif ($command === 'rollback') {
            $migration::down();
        } else {
            print "Invalid command. Use 'migrate' or 'rollback'.\n";
            exit(1);
        }
    } else {
        print "Migration class $className not found in file $file.\n";
    }
}

function toPascalCase($string) {
    // Convert underscores to spaces, capitalize each word, then remove spaces
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
}

function convertFilenameToClassName($filename) {
    // Remove the timestamp part and convert the remaining words to PascalCase
    $parts = explode('_', $filename, 5); // Split into max 5 parts to skip the first 4 (timestamp)
    
    // Check if the filename has enough parts
    if (count($parts) < 5) {
        throw new InvalidArgumentException("Filename '$filename' is not in the expected format with a timestamp prefix.");
    }

    // Combine all parts after the timestamp to form the class name
    $words = array_slice($parts, 4);

    // Convert to PascalCase
    return 'Migrations\\' . toPascalCase(implode('_', $words));
}


// Run: php cli/run_migrations.php migrate | rollback