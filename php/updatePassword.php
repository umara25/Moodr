<?php
/**
 * Password Update Handler
 * Allows users to change their account password
 * Validates current password and ensures new password meets requirements
 */

session_start();
include "connect.php";

// Ensure user is logged in before allowing password change
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Process password change request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get current username from session
    $username = $_SESSION['username'];
    
    // Retrieve form data
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];
    $currentPassword = $_POST['currentPassword'];
    
    // Validate that all fields are provided
    if (empty($newPassword) || empty($confirmNewPassword) || empty($currentPassword)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    // Verify new passwords match (double-check client-side validation)
    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['error'] = "New passwords do not match";
        header("Location: myprofile.php");
        exit();
    }
    
    // Enforce password strength requirements
    if (strlen($newPassword) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long";
        header("Location: myprofile.php");
        exit();
    }
    
    // Verify current password
    $stmt = $dbh->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($currentPassword, $user['password'])) {
        $_SESSION['error'] = "Current password is incorrect";
        header("Location: myprofile.php");
        exit();
    }
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password in database
    $stmt = $dbh->prepare("UPDATE users SET password = ? WHERE username = ?");
    
    if ($stmt->execute([$hashedPassword, $username])) {
        $_SESSION['success'] = "Password successfully updated";
    } else {
        $_SESSION['error'] = "Failed to update password";
    }
}

// Redirect back to profile page
header("Location: myprofile.php");
exit();
?>