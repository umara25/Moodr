<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $confirmDelete = isset($_POST['confirmDelete']);
    
    if (empty($password) || empty($confirmPassword) || !$confirmDelete) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: myprofile.php");
        exit();
    }
    
    $stmt = $dbh->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Incorrect password";
        header("Location: myprofile.php");
        exit();
    }
    
    try {
        $dbh->beginTransaction();
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=0');
        
        $tables_to_check = ['reviews', 'posts', 'likes', 'messages'];
        
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
        
        $stmt = $dbh->prepare("DELETE FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        $dbh->commit();
        
        session_destroy();
        
        session_start();
        $_SESSION['success'] = "Your account has been successfully deleted";
        header("Location: login.php");
        exit();
        
    } catch(PDOException $e) {
        $dbh->rollBack();
        
        $dbh->exec('SET FOREIGN_KEY_CHECKS=1');
        
        $_SESSION['error'] = "Failed to delete account: " . $e->getMessage();
        header("Location: myprofile.php");
        exit();
    }
}

header("Location: myprofile.php");
exit();
?> 