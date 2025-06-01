<?php
/**
 * Bio Update Handler
 * Allows users to update their profile bio/description
 * Updates both database and session data
 */

session_start();
include "connect.php";

// Process bio update if form data and user session are present
if (isset($_POST['bio']) && isset($_SESSION['username'])) {
    $bio = $_POST['bio'];
    $username = $_SESSION['username'];

    // Update bio in database
    $stmt = $dbh->prepare("UPDATE users SET bio = ? WHERE username = ?");
    $stmt->execute([$bio, $username]);

    // Update session data to reflect change
    $_SESSION['bio'] = $bio;
}

// Redirect back to profile page
header("Location: myprofile.php");
exit();
