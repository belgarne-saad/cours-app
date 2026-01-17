<?php
/**
 * GLOBAL AUTHENTICATION GATE
 * 
 * This file must be included at the TOP of every page in the application.
 * It checks if a user is authenticated and redirects to login if not.
 * 
 * HOW IT WORKS:
 * 1. Starts session if not already started
 * 2. Checks if user_id is set in session (authenticated)
 * 3. If NOT authenticated: Redirect to auth/index.php
 * 4. If authenticated: Allow page access
 * 
 * SESSION VARIABLES:
 * - $_SESSION['user_id']: Student ID (for students)
 * - $_SESSION['user_type']: 'student' or 'admin'
 * - $_SESSION['user_name']: Student/Admin name
 * - $_SESSION['username']: Admin username only
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is authenticated
$is_authenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// If not authenticated, redirect to auth page
if (!$is_authenticated) {
    // Don't redirect if already on auth pages to prevent infinite loop
    $current_file = basename($_SERVER['PHP_SELF']);
    $auth_pages = ['index.php', 'student_login.php', 'student_register.php', 'admin_login.php', 'logout.php'];
    $current_dir = basename(dirname($_SERVER['PHP_SELF']));
    
    // Allow auth pages to be accessed without authentication
    if (!($current_dir === 'auth' && in_array($current_file, $auth_pages))) {
        header("Location: " . dirname(dirname($_SERVER['PHP_SELF'])) . "/auth/index.php");
        exit();
    }
}
?>
