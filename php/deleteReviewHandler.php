<?php 
/** 
 * Handles delete review requests from reviewListener.js 
 * Receives a reviewID and deletes it 
 */

include "connect.php";


$id = filter_input(INPUT_GET,"id");

// Get img path to delete from server
$cmd = "SELECT `img_path` FROM reviews WHERE reviewID = ?";
$stmt = $dbh->prepare($cmd);
$suc = $stmt->execute([$id]);

if($row = $stmt->fetch()){ 
    if(file_exists($row['img_path'])){ 
        unlink($row['img_path']);   // Delete img 
    }
}


// Delete review from table
$cmd = "DELETE FROM reviews WHERE reviewID = ?"; 
$stmt = $dbh->prepare($cmd);
$suc = $stmt->execute([$id]);





