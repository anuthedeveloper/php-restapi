#!/usr/bin/env php
<?php

// Check if the migration name is provided as an argument
if ($argc < 2) {
    echo "Usage: generate_migration.php <migration_name>\n";
    exit(1);
}

// Get the migration name and sanitize it
$migrationName = preg_replace('/[^a-zA-Z0-9_]/', '', $argv[1]);
if (!$migrationName) {
    echo "Invalid migration name. Only alphanumeric and underscore characters are allowed.\n";
    exit(1);
}

// Generate a timestamp for the migration file
$timestamp = date('Ymd_His');
$filename = $timestamp . '_' . $migrationName . '.php';
$filepath = __DIR__ . '/../migrations/' . $filename;

// Template for the migration file content
$template = <<<EOT
<?php

function up(PDO \$db) {
    // Add your migration code here
}

function down(PDO \$db) {
    // Add your rollback code here
}

EOT;

// Write the template to a new file in the migrations directory
if (file_put_contents($filepath, $template) !== false) {
    echo "Migration file created: $filepath\n";
} else {
    echo "Failed to create migration file.\n";
}


// chmod +x cli/generate_migration.php

// ./cli/generate_migration.php create_users_table
