#!/usr/bin/env php
<?php
// Load environment variables and bootstrap the application
require_once __DIR__ . '/../bootstrap/bootstrap.php';

use App\Models\User;

// Example function to seed the database with user data
function seedUsers() {
    $users = [
        ['fullname' => 'Alice', 'email' => 'alice@example.com'],
        ['fullname' => 'Bob', 'email' => 'bob@example.com'],
    ];

    foreach ($users as $userData) {
        $user = User::create($userData); // Hypothetical create method
        print "Created user: {$user['fullname']} ({$user['email']})\n";
    }
}

seedUsers();
print "Database seeding complete.\n";

// Set Execute Permissions: Run the following command in the terminal to make seed_database.php executable:
// chmod +x cli/seed_database.php

// Run the Script: Now, you can execute the script directly from the command line:
// ./cli/seed_database.php
