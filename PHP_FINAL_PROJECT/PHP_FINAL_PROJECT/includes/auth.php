<?php
/**
 * Authentication Check
 * This file ensures that only logged-in users can access protected pages
 */

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store the current page URL for redirect after login
    $current_page = $_SERVER['REQUEST_URI'];
    
    // Redirect to login page with return URL
    header('Location: login.php?redirect=' . urlencode($current_page));
    exit;
}

// Optional: Check if session is still valid (you can add session timeout logic here)
// For example, check if session has expired after certain time
/*
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 3600) {
    // Session expired after 1 hour
    session_destroy();
    header('Location: login.php?message=session_expired');
    exit;
}
$_SESSION['last_activity'] = time();
*/
?>
