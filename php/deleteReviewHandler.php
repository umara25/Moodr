<?php

/** 
 * Handles delete review requests from reviewListener.js 
 * Receives a reviewID and deletes it 
 */


$id = filter_input(INPUT_GET, "id");
if ($id !== null) {
    include "connect.php";

    // Get img path to delete from server
    $cmd = "SELECT `img_path` FROM reviews WHERE reviewID = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$id]);

    if ($row = $stmt->fetch()) { // review with ID exists
        if (file_exists($row['img_path'])) {
            unlink($row['img_path']);   // Delete img 
        }
    }else{ // Review does not exist
        echo (-1); // Error message
        exit;
    }


    // Delete review from table
    $cmd = "DELETE FROM reviews WHERE reviewID = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$id]);

    if($suc && $stmt->rowCount() > 0){  // Successfully deleted a review
        echo (1);
    }else{ 
        echo(-1);
    }
}
