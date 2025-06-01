<?php
/**
 * Image Upload Handler
 * Contains utility functions for handling image uploads
 * Includes validation, file renaming, and path retrieval functions
 */

/** 
 * Image Upload Verification Function
 * Takes fileName, fileType, fileSize, fileTmp (Temporary file path), and 
 * uploadDir (Path to directory you want to upload) i.e '../exDirectory/'
 *
 * Validates if file is a valid image and uploads it successfully 
 * Returns path to uploaded file if valid, otherwise returns false
 */
function image_verify($fileName, $fileType, $fileSize, $fileTmp, $uploadDir)
{
    // Define allowed image file types
    $valid_types = ['image/jpeg', 'image/jpg', 'image/png'];

    // Validate file type - must be an image
    if (in_array($fileType, $valid_types) === false) {
        return false;
    }

    $upload_max_size = 5 * 10 ** 6; // Maximum file size: 5 MB 

    // Validate file size - must be under limit
    if ($fileSize > $upload_max_size) {
        return false;
    }

    // Generate unique filename to prevent conflicts
    $new_name = renameFile($fileName);

    // Ensure upload directory is writable
    if (!is_writable($uploadDir)) {
        return false;
    }

    // Create full path for file upload
    $path = $uploadDir . $new_name;

    // Move uploaded file from temporary location to final destination
    $upload_file = move_uploaded_file($fileTmp, $path);

    // Verify file upload was successful 
    if ($upload_file) {
        // Uploaded successfully 
        return $path; // Return path to the uploaded file
    }

    return false; // Unable to upload the file
}

/** 
 * File Renaming Function
 * Takes the original file name and returns a new unique name
 */
function renameFile($fileName)
{
    $str = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    $len = 8;

    $shuffled_str = str_shuffle($str);  // Shuffle $str to get random characters

    // Create a random string by taking $len characters of shuffled string and appending current time
    $random_str = substr($shuffled_str, 0, $len) . time(); 

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);  // Extract file extension from original file name

    $new_name = $random_str . "." . $extension; // Combine random string and extension to create new file name

    // Check if file with the new name already exists in the upload directory
    if (file_exists("../ReviewImgs/" . $new_name)) {
        // If file name exists, recursively call renameFile() to generate a new name
        return renameFile($fileName); 
    } else {
        return $new_name;  // Return the unique file name
    }
}

/**
 * Profile Picture Path Retrieval Function
 * Selects profile picture from users table based on username
 * Returns path to the profile picture
 */
function get_pfp_path($username)
{
    include "connect.php";
    $cmd = "SELECT `pfp_path` FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$username]);
    if ($row = $stmt->fetch()) {
        return $row["pfp_path"]; // Return the profile picture path
    } else {
        return (-1); // Return -1 if no profile picture is found
    }
}
