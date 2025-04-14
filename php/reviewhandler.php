<?php 
session_start();
header('Content-Type: application/json'); 
date_default_timezone_set('America/New_York'); 
/** 
 * Handle storing reviews in database based on data sent from 
 * HTTP request initialized from reviewListener.js 
 */

//  var_dump($_FILES);

 if($_SESSION["role"] === "admin"){ 
    //They are an admin 

     // Receive POST params
    $title = filter_input(INPUT_POST,"title",FILTER_SANITIZE_SPECIAL_CHARS);
    $msg = filter_input(INPUT_POST,"msg",FILTER_SANITIZE_SPECIAL_CHARS);
    $score = filter_input(INPUT_POST,"score",FILTER_VALIDATE_FLOAT);
    $date = date('Y-m-d');


    if($title !== null && $msg !== null && $score !== null && $score !== false){ 
        //Params are ok 
        include "connect.php"; 

        // $cmd = "INSERT INTO reviews (username,title,text_body,date) VALUES (?,?,?,?)";
        // $stmt = $dbh->prepare($cmd);
        // $succ = $stmt->execute([$_SESSION["username"],$title,$msg,$date]);

        $succ = true;

        if($succ){ 


            // echo("title: ". $title . "\n");
            // echo("body:" . $msg. "\n");
            // echo("score:" . $score. "\n");
            // echo("date:" . $date. "\n");
            // echo("Files AA: " .var_dump($_FILES['image']));

            //$_FILES['image']['tmp_name'] Stores current file path to image


            //Upload file 

            //Extract file details from $_FILES array
            $fileTmpPath = $_FILES['image']['tmp_name'];  // Temporary file path
            $fileName = $_FILES['image']['name'];         // Original file name
            $fileSize = $_FILES['image']['size'];         // File size
            $fileType = $_FILES['image']['type'];         // File type

            // Santize file, replace any non alphanumeric characters with underscores
            $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $fileName);

            // Specify the directory where you want to save the file
            $uploadDir = '../ReviewImgs/';

            $path = $uploadDir . $fileName; // Upload to /ReviewImgs/ImageName

            // Check for file upload error 
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                // echo "File upload error: " . $_FILES['image']['error'];
                echo json_encode(-1);
                exit;
            }


            // if (file_exists($_FILES['image']['tmp_name'])) {
            //     // echo "Temporary file does exist.";
            // }

            // Check if direectory is writeable
            if (!is_writable($uploadDir)) {
                echo json_encode(-1);
                // echo "Upload directory is not writable.";
                exit;
            }
          
            // Upload from tmpPath to path
            if(move_uploaded_file($fileTmpPath,$path)){ 
                // File was uploaded successfully 
                // echo("Successfully uploaded " . $fileName);
            }else{ // Failed to upload
                // echo("FAILED TO UPLOAD FILE to ".$path);
                echo json_encode(-1);
            }

            echo json_encode(["username" => $_SESSION["username"], 
                              "title" => $title, 
                               "msg" => $msg , 
                               "score" => $score,
                               "date" => $date, 
                                "img" =>$path]);



        }else{
            echo json_encode(-1);
        }
    }



 } else {
    echo json_encode(-1);
 }
