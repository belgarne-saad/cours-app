<?php
/**
 * LOGOUT PAGE
 * 
 * Destroys the session and redirects to auth/index.php
 * Works for both students and admins.
 * 
 * HOW IT WORKS:
 * 1. Start session
 * 2. Destroy session completely
 * 3. Redirect to auth/index.php
 */

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session
session_destroy();

// Redirect to auth entry point
header("Location: index.php");
exit();
?>
