<?php
// Enable error display during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

// Create logs directory if it doesn't exist
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

// Test if error logging is working
error_log("Error logging initialized at " . date('Y-m-d H:i:s'));
echo "Error logging is set up. Check the logs directory for errors.";
?>
