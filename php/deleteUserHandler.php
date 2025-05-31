<?php
/** 
 * Delete User Processing
 * Checks if user is admin, if not deletes user.
 */

session_start();
include "connect.php";

$loggedIn = false;

// Checks if there is an Active Session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow logged in admin users to delete other users
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        // Sanitize the username input
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);

        // Validate username input
        if ($user===null || $user === "") {
            echo "Error: Invalid user.";
            exit();
        }

        // Check the target user's role before deletion
        $cmd = "SELECT `role` FROM users WHERE username=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$user]);
        $row = $stmt->fetch();

        // Prevent deletion of admin users
        if (!$success || $row["role"] === "admin") {
            echo (-1);
            exit();
        }

        // Delete regular user account
        if($row["role"]==="user"){
            $cmd = "DELETE FROM users WHERE username=?";
            $stmt = $dbh->prepare($cmd);
            $success = $stmt->execute([$user]);
            if (!$success) {
                echo "Error: Failed to delete from database.";
                exit();
            }
            else {
                echo "$user";
            }
        }
    }
} else {
    session_destroy();
    header('Location: login.php'); // redirects logged out session to the login page.
    exit();
}
