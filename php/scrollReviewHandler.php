<?php

/** 
 * Implement infinite scrolling: 
 * $_SESSION["rendered_reviews"] stores reviewID's of the reviews already on the page
 * $_SESSIOn["review_date"] stores the timestamp of the last review's timestamp
 * Script pulls 10 more posts from reviews table such that date is before last review and the 
 * ID is not one of the currently rendered ID's.
 * Creates array of Associative Arrays and JSON encodes it so javascript can treat response as objects
 * Easier to render that way
 */
session_start();    // Used to access $_SESSION["rendered_reviews] to see what reviews are already rendered
header('Content-Type: application/json');

// Check if rendered reviews, and review_date are set
if(isset($_SESSION["rendered_reviews"]) && isset($_SESSION["review_date"])){
    include "imageHandler.php";     // Using get_pfp_path function to retrieve pfp paths

    // Create string of comma separated "?,?,?,?..." placeholders for SQL command
    // # of placeholders = length of rendered_reviews array
    $placeholders = implode(",", array_fill(0, count($_SESSION["rendered_reviews"]), "?"));

    $args = array_merge([$_SESSION["review_date"]],$_SESSION["rendered_reviews"]);   // Create array of arguments

    include "connect.php";
    $cmd = "SELECT * FROM `reviews` WHERE `date` <= ? AND `reviewID` NOT IN ($placeholders) ORDER BY `date`
            DESC LIMIT 10";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute($args);

    $arr = [];
    $new_reviews = [];  // Store ID's of new reviews

    while ($row = $stmt->fetch()) {
        array_push($new_reviews,$row["reviewID"]); // Add ID to array
        $pfp_path = get_pfp_path($row["username"]);  // Use iamge handler function

        // Recreate Associative Array, was having issues when I didn't do this
        // JS is expecting object with below fields
        // {username: user, title: review title, msg: review body, score: review score, 
        //  date: date review posted, img: img path, pfp: pfp_path, id: reviewID}

        $a = [
            "id" => $row["reviewID"],
            "username" => $row["username"],
            "title" => $row["title"],
            "msg" => $row["text_body"],
            "img" => $row["img_path"],
            "score" => $row["score"],
            "date" => $row["date"]
        ];

        if ($pfp_path != -1) {
            // Managed to retrieve a pfp, so add it
            $a["pfp"] = $pfp_path;
        }
        array_push($arr, $a);  // Add each row to array

        $_SESSION["review_date"] = $row["date"];    // Ensures you get last time stamp
    }

    // Combine to keep track of new rendered reviews
    $_SESSION["rendered_reviews"] = array_merge($_SESSION["rendered_reviews"],$new_reviews); 
    
    sleep(1); // Add a little bit of a delay
    echo json_encode($arr);
}else{ 
    echo json_encode([]);
}

