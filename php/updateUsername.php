<?php
/**
 * Username Update Handler
 * Allows users to change their username
 * Validates password and checks username availability before updating
 */

session_start();
include "connect.php";

// Ensure user is logged in before allowing username change
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Process username change request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get current username from session
    $currentUsername = $_SESSION['username'];
    
    // Retrieve form data
    $newUsername = $_POST['newUsername'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // Validate that all fields are provided
    if (empty($newUsername) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    // Check if new username is already taken by another user
    $stmt = $dbh->prepare("SELECT * FROM users WHERE username = ? AND username != ?");
    $stmt->execute([$newUsername, $currentUsername]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username already taken";
        header("Location: myprofile.php");
        exit();
    }
    
    // Verify the provided password matches the current user's password
    $stmt = $dbh->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$currentUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($confirmPassword, $user['password'])) {
        $_SESSION['error'] = "Incorrect password";
        header("Location: myprofile.php");
        exit();
    }
    
    try {
        $dbh->beginTransaction();
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=0');
          $stmt = $dbh->prepare("INSERT INTO users (username, email, role, password, bio, pfp_path) 
                              SELECT ?, email, role, password, bio, pfp_path 
                              FROM users 
                              WHERE username = ?");
        $stmt->execute([$newUsername, $currentUsername]);
        
        $tables_to_check = ['reviews', 'posts', 'likes', 'messages'];
        
        foreach ($tables_to_check as $table) {
            $result = $dbh->query("SHOW TABLES LIKE '$table'");
            
            if ($result->rowCount() > 0) {
                if ($table == 'messages') {
                    $stmt = $dbh->prepare("UPDATE messages SET sender = ? WHERE sender = ?");
                    $stmt->execute([$newUsername, $currentUsername]);
                    
                    $stmt = $dbh->prepare("UPDATE messages SET receiver = ? WHERE receiver = ?");
                    $stmt->execute([$newUsername, $currentUsername]);
                } else {
                    $stmt = $dbh->prepare("UPDATE $table SET username = ? WHERE username = ?");
                    $stmt->execute([$newUsername, $currentUsername]);
                }
            }
        }
        
        $stmt = $dbh->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$currentUsername]);
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        $dbh->commit();
        
        // Update session with new username
        $_SESSION['username'] = $newUsername;
        $_SESSION['success'] = "Username successfully updated";
    } catch(PDOException $e) {
        $dbh->rollBack();
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        $_SESSION['error'] = "Failed to update username: " . $e->getMessage();
    }
}

// Redirect back to profile page
header("Location: myprofile.php");
exit();
?>