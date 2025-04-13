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


if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        $user = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);

        if ($user===null || $user === "") {
            echo "Error: Invalid user.";
            exit();
        }

        $cmd = "SELECT `role` FROM users WHERE username=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$user]);
        $row = $stmt->fetch();

        if (!$success || $row["role"] === "admin") {
            echo (-1);
            exit();
        }

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
