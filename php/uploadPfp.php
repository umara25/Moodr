<?php
/**
 * Profile Picture Upload Handler
 * Processes profile picture uploads for users
 * Validates image files and updates database with new picture path
 */

session_start();
include "connect.php";
include "imageHandler.php";

// Ensure user is logged in before allowing profile picture upload
if (!isset($_SESSION['username'])) {
    header("Location: myprofile.php");
    exit();
}

$username = $_SESSION['username'];

// Check if a file was actually uploaded
if (!isset($_FILES['pfp']) || $_FILES['pfp']['error'] === UPLOAD_ERR_NO_FILE) {
    $_SESSION['error'] = "You need to upload an image first";
    header("Location: myprofile.php");
    exit();
}

// Check for upload errors
if ($_FILES['pfp']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Error uploading profile picture";
    header("Location: myprofile.php");
    exit();
}

// Get file information for validation
$fileTmpPath = $_FILES['pfp']['tmp_name'];
$fileName = $_FILES['pfp']['name'];
$fileSize = $_FILES['pfp']['size'];
$fileType = $_FILES['pfp']['type'];

// Validate and upload the image using imageHandler functions
$path = image_verify($fileName, $fileType, $fileSize, $fileTmpPath, "../ProfileImgs/");

if ($path !== false) {
    $cmd = "UPDATE users SET pfp_path = ? WHERE username = ?";
    $stmt = $dbh->prepare($cmd);
    $stmt->execute([$path, $username]);
    
    $_SESSION['success'] = "Profile picture updated successfully";
} else {
    $_SESSION['error'] = "Invalid image file. Please use JPG, JPEG or PNG files under 5MB.";
}

header("Location: myprofile.php");
exit();
