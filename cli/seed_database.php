#!/usr/bin/env php
<?php
// Load environment variables and bootstrap the application
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use Models\User;

// Example function to seed the database with user data
function seedUsers() {
    $users = [
        ['name' => 'Alice', 'email' => 'alice@example.com'],
        ['name' => 'Bob', 'email' => 'bob@example.com'],
    ];

    foreach ($users as $userData) {
        $user = User::create($userData); // Hypothetical create method
        print "Created user: {$user['name']} ({$user['email']})\n";
    }
}

seedUsers();
print "Database seeding complete.\n";

// Set Execute Permissions: Run the following command in the terminal to make seed_database.php executable:
// chmod +x cli/seed_database.php

// Run the Script: Now, you can execute the script directly from the command line:
// ./cli/seed_database.php
