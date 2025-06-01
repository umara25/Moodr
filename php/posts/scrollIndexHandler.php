<?php
/** 
 * Index Page Infinite Scroll Handler
 * Implements infinite scrolling for announcement posts on the main dashboard
 * 
 * Uses session variables to track:
 * - $_SESSION["rendered_posts"]: Array of postIDs already displayed
 * - $_SESSION["post_date"]: Timestamp of the last post to determine pagination
 * 
 * Retrieves next batch of posts excluding already rendered ones
 * Returns JSON array for frontend JavaScript consumption
 */

session_start();    // Access session to track already rendered posts
header('Content-Type: application/json');

// Verify session variables are set for pagination
if(isset($_SESSION["rendered_posts"]) && isset($_SESSION["post_date"])){
    include "imageHandler.php";     // Import pfp path retrieval function

    // Generate SQL placeholders for excluding already rendered posts
    // Creates "?,?,?,?..." string matching the number of rendered posts
    $placeholders = implode(",", array_fill(0, count($_SESSION["rendered_posts"]), "?"));

    // Combine timestamp and rendered post IDs for SQL query parameters
    $args = array_merge([$_SESSION["post_date"]],$_SESSION["rendered_posts"]);

    include "connect.php";
    
    // Get next 5 posts that are older than last displayed post and not already shown
    $cmd = "SELECT * FROM `announcements` WHERE `date` <= ? AND `postId` NOT IN ($placeholders) ORDER BY `date` DESC LIMIT 5";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute($args);

    $arr = []; // Array to hold formatted post data
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

