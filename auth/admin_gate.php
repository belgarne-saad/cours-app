<?php
/**
 * ADMIN ACCESS CONTROL
 * 
 * Protects admin pages from non-admin access.
 * Must be included at the TOP of every admin page.
 */

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
$is_authenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// If not authenticated, redirect to auth
if (!$is_authenticated) {
    header("Location: " . dirname(dirname(__FILE__)) . "/auth/index.php");
    exit();
}

// Check if user is ADMIN
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Student trying to access admin page - redirect
    header("Location: " . dirname(dirname(__FILE__)) . "/auth/index.php");
    exit();
}
?>
