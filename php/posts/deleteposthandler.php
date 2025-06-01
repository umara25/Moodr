<?php
/** 
 * Delete Post Handler
 * Allows admin users to delete announcement posts
 * Validates user permissions and post ID before deletion
 */

session_start();
include "connect.php";

$loggedIn = false;

// Check if there is an active user session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow logged in admin users to delete posts
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        // Validate and sanitize the post ID input
        $postId = filter_input(INPUT_POST, "postId", FILTER_VALIDATE_INT);

        if (!$postId) {
            echo "Error: Invalid post ID.";
            exit();
        }

        // Delete the post from the announcements table
        $cmd = "DELETE FROM announcements WHERE postId=?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$postId]);
        
        if (!$success) {
            echo "Error: Failed to delete from database.";
        }
        else {
            echo "$postId"; // Return the deleted post ID on success
        }
    }
} else {
    // Redirect non-logged in users to login page
    session_destroy();
    header('Location: login.php');
    exit();
}
