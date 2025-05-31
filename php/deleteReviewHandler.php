<?php
/** 
 * Delete Review Handler
 * Handles delete review requests from reviewListener.js 
 * Receives a reviewID and deletes the review and associated image file
 */

// Get and validate the review ID from URL parameter
$id = filter_input(INPUT_GET, "id");
if ($id !== null) {
    include "connect.php";

    // Get image path to delete from server storage
    $cmd = "SELECT `img_path` FROM reviews WHERE reviewID = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$id]);

    if ($row = $stmt->fetch()) { 
        // Review with ID exists - delete associated image file
        if (file_exists($row['img_path'])) {
            unlink($row['img_path']);   // Remove image file from server
        }
    } else { 
        // Review does not exist - return error
        echo (-1);
        exit;
    }

    // Delete review record from database
    $cmd = "DELETE FROM reviews WHERE reviewID = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$id]);

    if($suc && $stmt->rowCount() > 0){  
        // Successfully deleted a review
        echo (1);
    } else { 
        // Failed to delete review
        echo(-1);
    }
}
