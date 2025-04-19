<?php

/** 
 * Stores image uploading handler
 * Has functions that verifies if file is valid (Is an image, uploaded successfully)
 * And a function that renames the file
 * And a function that retrieves the pfp path from the users database
 */


/** 
 * Takes fileName, fileType, fileSize, fileTmp (Temporary file path), and 
 * uploadDir (Path to directory you want to upload) i.e '../exDirectory/'
 *
 * Checks if it is an image and if it uploads successfully 
 * Returns path to uploaded file if valid 
 * Else Returns false
 */
function image_verify($fileName, $fileType, $fileSize, $fileTmp, $uploadDir)
{

    $valid_types = ['image/jpeg', 'image/jpg', 'image/png'];

    // File type check
    if (in_array($fileType, $valid_types) === false) {
        // Type is not inside valid types array 
        return false;
    }

    $upload_max_size = 5 * 10 ** 6; // 5 MB 

    // File size check
    if ($fileSize > $upload_max_size) {
        return false;
    }

    $new_name = renameFile($fileName);       // Rename file 

    // $uploadDir = "../ReviewImgs";           // Directory you want to upload file to 

    // Check if Directory is writeable
    if (!is_writable($uploadDir)) {
        return false;
    }

    $path = $uploadDir . $new_name;   // Add file name to path of file upload

    // Move from fileTmp to path 
    $upload_file = move_uploaded_file($fileTmp, $path);

    // File upload check 
    if ($upload_file) {
        // Uploaded successfully 
        return $path; // Return path
    }

    return false; // Unable to upload
}

/** 
 * Takes the file name and returns a new unique name
 */
function renameFile($fileName)
{
    $str = "123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    $len = 8;

    $shuffled_str = str_shuffle($str);  // Shuffle $str

    $random_str = substr($shuffled_str, 0, $len) . time(); // Take $len characters of shuffled string and append time()

    $extension = pathinfo($fileName, PATHINFO_EXTENSION);  // Get file extension

    $new_name = $random_str . "." . $extension;

    // Check if file somehow exists already
    if (file_exists("../ReviewImgs/" . $new_name)) {
        // File name exists in directory 
        return renameFile($fileName); //Call it again to generate new name
    } else {
        return $new_name;  // Unique name, so return it
    }
}

/**
 * Selects pfp from users column, based on username
 * Returns path to pfp
 */
function get_pfp_path($username)
{
    include "connect.php";
    $cmd = "SELECT `pfp_path` FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$username]);
    if ($suc) {
        return $stmt->fetch()["pfp_path"];
    } else {
        return (-1);
    }
}
