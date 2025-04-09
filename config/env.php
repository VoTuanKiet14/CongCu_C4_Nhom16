<?php
/**
 * Load environment variables from .env file
 */
function loadEnv() {
    $envFile = dirname(__DIR__) . '/.env';
    
    if (!file_exists($envFile)) {
        die('.env file not found');
    }
    
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignore comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Split by the first = character
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        // Remove quotes if present
        if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
            $value = $matches[1];
        }
        
        // Set the environment variable
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}

// Load environment variables when this file is included
loadEnv();
?>
