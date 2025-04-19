<?php
session_start();
include "connect.php";
include "imageHandler.php";

if (!isset($_SESSION['username'])) {
    header("Location: myprofile.php");
    exit();
}

$username = $_SESSION['username'];

if (!isset($_FILES['pfp']) || $_FILES['pfp']['error'] === UPLOAD_ERR_NO_FILE) {
    $_SESSION['error'] = "You need to upload an image first";
    header("Location: myprofile.php");
    exit();
}

if ($_FILES['pfp']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Error uploading profile picture";
    header("Location: myprofile.php");
    exit();
}

$fileTmpPath = $_FILES['pfp']['tmp_name'];
$fileName = $_FILES['pfp']['name'];
$fileSize = $_FILES['pfp']['size'];
$fileType = $_FILES['pfp']['type'];

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
