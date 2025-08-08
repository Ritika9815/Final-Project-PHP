<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/auth.php';

$user_id = $_GET['id'] ?? 0;

// Prevent users from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    header('Location: users.php');
    exit;
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php');
    exit;
}

// Delete user
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
if ($stmt->execute([$user_id])) {
    // Delete profile image if exists
    if ($user['profile_image'] && file_exists('uploads/' . $user['profile_image'])) {
        unlink('uploads/' . $user['profile_image']);
    }
}

// Redirect back to users page
header('Location: users.php');
exit;
?>
