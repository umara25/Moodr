<?php
/** 
 * Delete Post Processing
 * Checks if user is admin, then allows admin to delete post.
 */

session_start();
include "connect.php";

$loggedIn = false;
//test
// Checks if there is an Active Session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}


if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        $postId = filter_input(INPUT_POST, "postId", FILTER_VALIDATE_INT);

        if (!$postId) {
            echo "Error: Invalid post ID.";
            exit();
        }

        $cmd = "DELETE FROM announcements WHERE postId=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$postId]);
        if (!$success) {
            echo "Error: Failed to insert into database.";
        }
        else {
            echo "$postId";
        }
    }
} else {
    session_destroy();
    header('Location: login.php'); // redirects logged out session to the login page.
    exit();
}
