<?php
/**
 * Delete Profile Handler
 * Allows users to permanently delete their account and all associated data
 * Includes password verification and cleanup of related files and database records
 */

session_start();
include "connect.php";

// Redirect to login if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Process the delete profile request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    
    // Get form inputs
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $confirmDelete = isset($_POST['confirmDelete']);
    
    // Validate all required fields are provided
    if (empty($password) || empty($confirmPassword) || !$confirmDelete) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    // Verify passwords match
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: myprofile.php");
        exit();
    }
    
    // Get user's current password and profile picture path
    $stmt = $dbh->prepare("SELECT password, pfp_path FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verify the entered password is correct
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Incorrect password";
        header("Location: myprofile.php");
        exit();
    }
    
    try {
        // Start database transaction for data integrity
        $dbh->beginTransaction();
        
        // Delete profile picture file if it exists
        if (!empty($user['pfp_path']) && file_exists($user['pfp_path'])) {
            unlink($user['pfp_path']);
        }
        
        // Temporarily disable foreign key checks for deletion
        $dbh->exec('SET FOREIGN_KEY_CHECKS=0');
        
        // List of tables that might contain user data
        $tables_to_check = ['reviews', 'posts', 'likes', 'messages'];
        
        // Clean up user data from all related tables
        foreach ($tables_to_check as $table) {
            $result = $dbh->query("SHOW TABLES LIKE '$table'");
            
            if ($result->rowCount() > 0) {
                if ($table == 'messages') {
                    $stmt = $dbh->prepare("DELETE FROM messages WHERE sender = ? OR receiver = ?");
                    $stmt->execute([$username, $username]);
                } else {
                    $stmt = $dbh->prepare("DELETE FROM $table WHERE username = ?");
                    $stmt->execute([$username]);
                }
            }
        }
        
        // Delete the user record from the users table
        $stmt = $dbh->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        // Re-enable foreign key checks
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        // Commit the transaction
        $dbh->commit();
        
        // Destroy the session and redirect to login
        session_destroy();
        
        session_start();
        $_SESSION['success'] = "Your account has been successfully deleted";
        header("Location: login.php");
        exit();
        
    } catch(PDOException $e) {
        // Rollback the transaction on error
        $dbh->rollBack();
        
        // Ensure foreign key checks are re-enabled
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        $_SESSION['error'] = "Failed to delete account: " . $e->getMessage();
        header("Location: myprofile.php");
        exit();
    }
}

// Redirect to profile page if not a POST request
header("Location: myprofile.php");
exit();
?>