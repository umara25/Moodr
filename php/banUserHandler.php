<?php
/** 
 * Ban/Unban User Handler
 * Handles the banning and unbanning of users by admin
 * Toggles user status between active and inactive
 */

session_start();
include "connect.php";

$loggedIn = false;

// Check if there is an active session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow admin users to ban/unban other users
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        // Sanitize the input username
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);

        // Validate the username input
        if ($user===null || $user === "") {
            echo "Error: Invalid user.";
            exit();
        }

        // Get the user's current role and status from database
        $cmd = "SELECT `role`, `status` FROM users WHERE username=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$user]);
        $row = $stmt->fetch();

        // Don't allow banning admin users
        if (!$success || $row["role"] === "admin") {
            echo (-1);
            exit();
        }

        // Toggle user status: active -> inactive (ban)
        if($row["role"]==="user" && $row["status"]==="active"){
            $cmd = "UPDATE `users` SET `status`=? WHERE `username`=?";
            $stmt = $dbh->prepare($cmd);
            $success = $stmt->execute(["inactive",$user]);
            if (!$success) {
                echo "Error: Failed to change status.";
                exit();
            }
            else {
                echo 1; // Success: user banned
            }
        }
        // Toggle user status: inactive -> active (unban)
        else if($row["role"]==="user" && $row["status"]==="inactive"){
            $cmd = "UPDATE `users` SET `status`=? WHERE `username`=?";
            $stmt = $dbh->prepare($cmd);
            $success = $stmt->execute(["active",$user]);
            if (!$success) {
                echo "Error: Failed to change status.";
                exit();
            }
            else {
                echo 2; // Success: user unbanned
            }
        }
    }
} else {
    // Redirect non-logged in users to login page
    session_destroy();
    header('Location: login.php');
    exit();
}
