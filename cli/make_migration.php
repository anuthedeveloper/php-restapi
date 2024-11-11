#!/usr/bin/env php
<?php

// Check if the migration name is provided as an argument
if ($argc < 2) {
    print "Usage: make_migration.php <migration_name>\n";
    exit(1);
}

// Get the migration name and sanitize it
$migrationName = preg_replace('/[^a-zA-Z0-9_]/', '', $argv[1]);
if (!$migrationName) {
    print "Invalid migration name. Only alphanumeric and underscore characters are allowed.\n";
    exit(1);
}

function toPascalCase($string) {
    // Convert underscores to spaces, capitalize each word, then remove spaces
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
}

function extractTableName($string) {
    // Regular expression to capture the middle part between `create_` and `_table`
    if (preg_match('/^create_(.*)_table$/', $string, $matches)) {
        return $matches[1];
    }
    return null; // Return null if the pattern doesn't match
}

// Generate a timestamp for the migration file
$timestamp = date('Y_m_d_His');
$filename = $timestamp . '_' . $migrationName . '.php';
$filepath = __DIR__ . '/../migrations/' . $filename;

// Table name extraction
$tableName = extractTableName($migrationName);
// Class name convertion
$className = toPascalCase($migrationName);

// Template for the migration file content
$template = <<<EOT
<?php
// migrations/$filename
namespace Migrations;

use App\Schemas\MigrationInterface;
use App\Schemas\Schema;

class $className implements MigrationInterface {
    public static function up() {
        Schema::createTable('$tableName', [
            'id' => 'INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ]);
    }

    public static function down() {
        Schema::dropTable('$tableName');
    }
}

EOT;

// Write the template to a new file in the migrations directory
if (file_put_contents($filepath, $template) !== false) {
    print "Migration file created: $filepath\n";
} else {
    print "Failed to create migration file.\n";
}


// chmod +x cli/make_migration.php

// ./cli/make_migration.php create_users_table
