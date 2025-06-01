<?php
/** 
 * Post Creation Handler
 * Allows admin users to create public announcements/posts
 * Validates user permissions and sanitizes input data
 */

session_start();
include "connect.php";
include "imageHandler.php"; // Using get_pfp_path()
date_default_timezone_set('America/New_York'); // Set timezone to prevent date errors

$loggedIn = false;

// Check if there is an active user session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

// Only allow logged in admin users to create posts
if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        // Sanitize form inputs to prevent XSS attacks
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS);
    }
} else {
    // Redirect non-logged in users to login page
    session_destroy();
    header('Location: login.php');
    exit();
}

// Insert new announcement into database
$cmd = "INSERT INTO announcements (username, title, message) VALUES (?,?,?)";
$stmt = $dbh->prepare($cmd);
$success = $stmt->execute([$_SESSION["username"], $title, $message]);

if (!$success) {
    echo "Error: Failed to insert into database.";
    exit();
}

// Redirect back to index page after successful post creation
header('Location: index.php');
exit();