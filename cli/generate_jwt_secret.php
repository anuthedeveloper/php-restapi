<?php

// Generate a random 32-byte JWT secret and base64 encode it
function generateJwtSecret($length = 32) {
    return base64_encode(random_bytes($length));
}

// Path to your .env file
$envPath = __DIR__ . '/../.env';

// Generate the JWT secret
$newSecret = generateJwtSecret();
print "Generated JWT Secret: $newSecret\n";

// Read the .env file
$envContent = file_get_contents($envPath);

// Check if .env file was read successfully
if ($envContent === false) {
    print "Error: Could not read .env file.\n";
    exit(1);
}

// Replace the JWT_SECRET key in the .env file (or add it if it doesn't exist)
if (preg_match('/^JWT_SECRET=.*$/m', $envContent)) {
    // Update existing JWT_SECRET
    $newEnvContent = preg_replace('/^JWT_SECRET=.*$/m', "JWT_SECRET=$newSecret", $envContent);
} else {
    // Append JWT_SECRET if it doesn't exist
    $newEnvContent = $envContent . "\nJWT_SECRET=$newSecret";
}

// Write the updated content back to the .env file
if (file_put_contents($envPath, $newEnvContent) === false) {
    print "Error: Could not write to .env file.\n";
    exit(1);
}

print "JWT_SECRET updated successfully in .env file.\n";

// php generate_jwt_secret.php