<?php
/**
 * Review Creation Handler
 * Processes review submissions from admin users
 * Handles review data validation, image uploads, and database storage
 */

session_start();
header('Content-Type: application/json');
date_default_timezone_set('America/New_York');

// Only admin users can create reviews
if ($_SESSION["role"] === "admin") {
    include "connect.php";
    include "imageHandler.php"; // Used for file uploading and verification and pfp pulling

    // Receive and sanitize POST parameters
    $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
    $msg = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS);
    $score = filter_input(INPUT_POST, "score", FILTER_VALIDATE_FLOAT);
    $date = date('Y-m-d H:i:s'); // Current timestamp

    // Validate all required parameters and their constraints
    if (
        $title !== null && $msg !== null && $score !== null && $score !== false
        && strlen($title) <= 30 && strlen($msg) <= 400 && strlen($title) > 0  && strlen($msg) > 0
        && $score <= 10 && $score >= 0
    ) {
        // Parameters are valid - proceed with review creation
        
        // Generate unique review ID
        $id = bin2hex(random_bytes(4));     // Generates 4 random bytes, converts to 8-char hex string

        // Check if generated ID already exists in database
        $cmd = "SELECT * FROM reviews WHERE reviewID = ?";
        $stmt = $dbh->prepare($cmd);
        $suc = $stmt->execute([$id]);

        // Keep generating new IDs until we find a unique one
        while ($row = $stmt->fetch()) {
            $id = bin2hex(random_bytes(4));
            $suc = $stmt->execute([$id]);
        }

        //Image was sent 
        if (isset($_FILES['image'])) {


            // Check for file upload error 
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(-1);
                exit;
            }

            //Extract file details from $_FILES array
            $fileTmpPath = $_FILES['image']['tmp_name'];  // Temporary file path
            $fileName = $_FILES['image']['name'];         // Original file name
            $fileSize = $_FILES['image']['size'];         // File size
            $fileType = $_FILES['image']['type'];         // File type

            $path = image_verify($fileName, $fileType, $fileSize, $fileTmpPath, "../ReviewImgs/");  // Call image_verify function

            if ($path !== false) {

                //Insert into database (image)
                $cmd = "INSERT INTO reviews (`reviewID`,`username`,`title`,`text_body`,`date`,`score`,`img_path`) VALUES (?,?,?,?,?,?,?)";
                $stmt = $dbh->prepare($cmd);
                $succ = $stmt->execute([$id, $_SESSION["username"], $title, $msg, $date, $score, $path]);

                $arr = [
                    "username" => $_SESSION["username"],
                    "title" => $title,
                    "msg" => $msg,
                    "score" => $score,
                    "date" => $date,
                    "img" => $path,
                    "id" => $id
                ];

                $pfp_path = get_pfp_path($_SESSION["username"]);  // Get pfp 

                if(file_exists($pfp_path)){ 
                    // Pfp file exists, so add it to arr
                    $arr["pfp"] = $pfp_path;
                }

                if ($succ) { // Successfully inserted into DB
                    echo json_encode($arr); //Echo AA with image path 
                } else {  //Failed to insert into databse
                    echo json_encode(-1);
                }
            } else { // Failed to upload image
                echo json_encode(-1);
            }
        } else { // No image sent 

            // Insert into DB (no image) 
            $cmd = "INSERT INTO reviews (`reviewID`,`username`,`title`,`text_body`,`date`,`score`) VALUES (?,?,?,?,?,?)";
            $stmt = $dbh->prepare($cmd);
            $succ = $stmt->execute([$id, $_SESSION["username"], $title, $msg, $date, $score]);

            $arr = [
                "username" => $_SESSION["username"],
                "title" => $title,
                "msg" => $msg,
                "score" => $score,
                "date" => $date,
                "id" => $id
            ]; // Construct Associative array to echo

            $pfp_path = get_pfp_path($_SESSION["username"]);  // Get pfp  
            if (file_exists($pfp_path)) {
                // Pfp file exists, so add it to arr
                $arr["pfp"] = $pfp_path;
            }
            if ($succ) { // Successfully inserted into DB
                echo json_encode($arr);
            } else { //Failed to insert into database 
                echo json_encode(-1);
            }
        }
    } else { // Params are invalid
        echo json_encode(-1);
    }
}
