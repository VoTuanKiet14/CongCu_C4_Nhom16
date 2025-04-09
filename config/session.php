<?php
// Set session configuration for better security and reliability
// These must be set BEFORE session_start()
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); 
ini_set('session.gc_maxlifetime', 28800); // 8 hours
ini_set('session.cookie_lifetime', 0); 

// Custom session path to avoid permission issues
$sessionPath = dirname(__DIR__) . '/temp/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0777, true);
}
session_save_path($sessionPath);

// Now we can start the session
session_start();
?>