<?php

/** 
 * Implement infinite scrolling: 
 * $_SESSION["rendered_posts"] stores postID's of the posts already on the page
 * $_SESSIOn["post_date"] stores the timestamp of the last post's timestamp
 * Script pulls 10 more posts from posts table such that date is before last post and the 
 * ID is not one of the currently rendered ID's.
 * Creates array of Associative Arrays and JSON encodes it so javascript can treat response as objects
 * Easier to render that way
 */
session_start();    // Used to access $_SESSION["rendered_posts] to see what posts are already rendered
header('Content-Type: application/json');

// Check if rendered posts, and post_date are set
if(isset($_SESSION["rendered_posts"]) && isset($_SESSION["post_date"])){
    include "imageHandler.php";     // Using get_pfp_path function to retrieve pfp paths

    // Create string of comma separated "?,?,?,?..." placeholders for SQL command
    // # of placeholders = length of rendered_posts array
    $placeholders = implode(",", array_fill(0, count($_SESSION["rendered_posts"]), "?"));

    $args = array_merge([$_SESSION["post_date"]],$_SESSION["rendered_posts"]);   // Create array of arguments

    include "connect.php";
    $cmd = "SELECT * FROM `announcements` WHERE `date` <= ? AND `postId` NOT IN ($placeholders) ORDER BY `date` DESC LIMIT 5";
    // $cmd = "SELECT * FROM `announcements` ORDER BY `date` DESC LIMIT 5";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute($args);

    $arr = [];
    $new_posts = [];  // Store ID's of new posts

    while ($row = $stmt->fetch()) {
        array_push($new_posts,$row["postId"]); // Add ID to array
        $pfp_path = get_pfp_path($row["username"]);  // Use iamge handler function

        // Recreate Associative Array, was having issues when I didn't do this
        // JS is expecting object with below fields
        // {username: user, title: post title, msg: post body, score: post score, 
        //  date: date post posted, img: img path, pfp: pfp_path, id: postID}

        $a = [
            "id" => $row["postId"],
            "username" => $row["username"],
            "title" => $row["title"],
            "msg" => $row["message"],
            "date" => $row["date"]
        ];

        // Not required for announcements page:
        if (file_exists($pfp_path)){
            // Managed to retrieve a pfp, so add it
            $a["pfp"] = $pfp_path;
        }
        array_push($arr, $a);  // Add each row to array

        $_SESSION["post_date"] = $row["date"];    // Ensures you get last time stamp
    }

    // Combine to keep track of new rendered posts
    $_SESSION["rendered_posts"] = array_merge($_SESSION["rendered_posts"],$new_posts); 
    
    sleep(1); // Add a little bit of a delay
    echo json_encode($arr);
}else{ 
    echo json_encode([]);
}

