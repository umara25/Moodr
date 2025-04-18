<?php
session_start();
include "connect.php";

// Only process if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get current username from session
    $username = $_SESSION['username'];
    
    // Get form data
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];
    $currentPassword = $_POST['currentPassword'];
    
    // Validate inputs
    if (empty($newPassword) || empty($confirmNewPassword) || empty($currentPassword)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    // Verify passwords match (double-check even though we have JS validation)
    if ($newPassword !== $confirmNewPassword) {
        $_SESSION['error'] = "New passwords do not match";
        header("Location: myprofile.php");
        exit();
    }
    
    // Check password strength (optional)
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