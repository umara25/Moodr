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
    include "connect.php";

    // Receive POST params
    $title = filter_input(INPUT_POST,"title",FILTER_SANITIZE_SPECIAL_CHARS);
    $msg = filter_input(INPUT_POST,"msg",FILTER_SANITIZE_SPECIAL_CHARS);
    $score = filter_input(INPUT_POST,"score",FILTER_VALIDATE_FLOAT);
    $date = date('Y-m-d H:i:s');


    if($title !== null && $msg !== null && $score !== null && $score !== false 
       && strlen($title) <= 30 && strlen($msg) <= 400){ 
        //Params are ok 

        //Image was sent 
        if(isset($_FILES['image'])){ 
            include "imageHandler.php"; // Used for file uploading and verification

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

            $path = image_verify($fileName,$fileType,$fileSize,$fileTmpPath,"../ReviewImgs/");  // Call image_verify function

            if($path !== false){ 
                //Insert into database (image)
                $cmd = "INSERT INTO reviews (`username`,`title`,`text_body`,`date`,`score`,`img_path`) VALUES (?,?,?,?,?,?)";
                $stmt = $dbh->prepare($cmd);
                $succ = $stmt->execute([$_SESSION["username"],$title,$msg,$date,$score,$path]);

                if($succ){ // Successfully inserted into DB
                    echo json_encode(["username" => $_SESSION["username"], 
                                    "title" => $title, 
                                    "msg" => $msg , 
                                    "score" => $score,
                                    "date" => $date, 
                                    "img" =>$path ]
                                    //  "fileDetails" =>$_FILES['image']] 
                                      ); //Echo AA with image path 
                }else{  //Failed to insert into databse
                    echo json_encode(-1);
                }
            }else{ // Failed to upload image
                echo json_encode(-1);
            }
        }else{ // No image sent 

            // Insert into DB (no image) 
            $cmd = "INSERT INTO reviews (`username`,`title`,`text_body`,`date`,`score`) VALUES (?,?,?,?,?)";
            $stmt = $dbh->prepare($cmd);
            $succ = $stmt->execute([$_SESSION["username"],$title,$msg,$date,$score]);

            if($succ){ // Successfully inserted into DB
                echo json_encode(["username" => $_SESSION["username"], 
                                "title" => $title, 
                                "msg" => $msg , 
                                "score" => $score,
                                "date" => $date]); 
            }else{ //Failed to insert into database 
                echo json_encode(-1); 
            }
        }
    }else{ // Params are invalid
        echo json_encode(-1);
    }
}
    
 