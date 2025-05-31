<?php
/**
 * Get Profile Picture Handler
 * Retrieves and serves the user's profile picture file
 * Sets appropriate content headers based on file type
 */

session_start();
include "connect.php";

// Ensure user is logged in before serving profile picture
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit();
}

$username = $_SESSION['username'];

// Get the profile picture path from database
$cmd = "SELECT pfp_path FROM users WHERE username = ?";
$stmt = $dbh->prepare($cmd);
$stmt->execute([$username]);

if ($row = $stmt->fetch()) {
    // Check if profile picture file exists
    if ($row["pfp_path"] && file_exists($row["pfp_path"])) {
        // Determine file type and set appropriate header
        $file_extension = pathinfo($row["pfp_path"], PATHINFO_EXTENSION);
        
        switch($file_extension) {
            case 'jpg':
            case 'jpeg':
                header("Content-Type: image/jpeg");
                break;
            case 'png':
                header("Content-Type: image/png");
                break;
            default:
                header("Content-Type: image/jpeg");
        }
        
        readfile($row["pfp_path"]);
        exit();
    }
}

// Fallback to default profile picture if no valid picture is found
readfile("../images/defaultpfp.jpg");
