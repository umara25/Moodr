<?php
/** 
 * Make Admin Handler
 * Allows existing admin users to promote regular users to admin status
 * Validates user permissions and target user eligibility
 */

session_start();
include "connect.php";

$loggedIn = false;

// Check if there is an active user session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow logged in admin users to promote others
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        // Sanitize the target username input
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);

        // Validate username input
        if ($user===null || $user === "") {
            echo "Error: Invalid user.";
            exit();
        }

        // Check the target user's current role
        $cmd = "SELECT `role` FROM users WHERE username=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$user]);
        $row = $stmt->fetch();

        // Prevent promoting users who are already admin
        if (!$success || $row["role"] === "admin") {
            echo (-1);
            exit();
        }

        // Promote regular user to admin
        if($row["role"]==="user"){
            $cmd = "UPDATE `users` SET `role`=? WHERE `username`=?";
            $stmt = $dbh->prepare($cmd);
            $success = $stmt->execute(["admin",$user]);
            if (!$success) {
                echo "Error: Failed to change role.";
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
