<?php
/** 
 * Reset Admin Handler
 * Allows current admin to demote all other users from admin status
 * Preserves the original 'Admin' account while converting all others to regular users
 */

session_start();
include "connect.php";

$loggedIn = false;

// Check if there is an active user session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow logged in admin users to reset admin permissions
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {

        // Demote all admin users except the original 'Admin' account
        $cmd = "UPDATE `users` SET `role`=? WHERE `username` != 'Admin'";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute(["user"]);
        
        if (!$success) {
            echo (-1); // Return error code
            exit();
        }
        else {
            echo 1; // Return success code
        }
    }
} else {
    session_destroy();
    header('Location: login.php'); // redirects logged out session to the login page.
    exit();
}
