<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    
    $email = $_POST['email'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if (empty($email) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required";
        header("Location: myprofile.php");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: myprofile.php");
        exit();
    }
    
    $stmt = $dbh->prepare("SELECT email FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($email !== $currentUser['email']) {
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = ? AND username != ?");
        $stmt->execute([$email, $username]);
        
        if ($stmt->rowCount() > 0) {
            $_SESSION['error'] = "Email already in use by another account";
            header("Location: myprofile.php");
            exit();
        }
    }
    
    $stmt = $dbh->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!password_verify($confirmPassword, $user['password'])) {
        $_SESSION['error'] = "Incorrect password";
        header("Location: myprofile.php");
        exit();
    }
    
    $stmt = $dbh->prepare("UPDATE users SET email = ? WHERE username = ?");
    
    if ($stmt->execute([$email, $username])) {
        $_SESSION['success'] = "Personal information successfully updated";
    } else {
        $_SESSION['error'] = "Failed to update personal information";
    }
}

header("Location: myprofile.php");
exit();
?> 