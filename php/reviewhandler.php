<?php 
session_start();
header('Content-Type: application/json'); 
date_default_timezone_set('America/New_York'); 
/** 
 * Handle storing reviews in database based on data sent from 
 * HTTP request initialized from reviewListener.js 
 */

 if($_SESSION["role"] === "admin"){ 
    //They are an admin 

     // Receive GET params
    $title = filter_input(INPUT_GET,"title",FILTER_SANITIZE_SPECIAL_CHARS);
    $msg = filter_input(INPUT_GET,"msg",FILTER_SANITIZE_SPECIAL_CHARS);
    $score = filter_input(INPUT_GET,"score",FILTER_VALIDATE_INT);
    $date = date('y-m-d');


    if($title !== null && $msg !== null && $score !== null && $score !== false){ 
        //Params are ok 
        include "connect.php"; 

        // $cmd = "INSERT INTO reviews (username,title,text_body,date) VALUES (?,?,?,?)";
        // $stmt = $dbh->prepare($cmd);
        // $succ = $stmt->execute([$_SESSION["username"],$title,$msg,$date]);

        $succ = true;

        if($succ){ 
            echo json_encode(["username" => $_SESSION["username"], 
                              "title" => $title, 
                               "msg" => $msg , 
                               "score" => $score,
                               "date" => $date ]);
        }else{
            echo json_encode(-1);
        }
    }



 } else {
    echo json_encode(-1);
 }
