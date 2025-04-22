<?php

/** 
 * Implement infinite scrolling: tell it what table and time of last post on screen 
 * and it pulls 10 more posts from specific table such that time is before last post
 * Creates array of objects and JSON encodes it so javascript can treat response as objects
 * Easier to render that way
 */

// Receive inputs, no need to sanitize since not displaying them

header('Content-Type: application/json');
$time = filter_input(INPUT_GET, "time");


if ($time !== null) {
    include "imageHandler.php";     // Using get_pfp_path function to retrieve pfp paths

    $time = urldecode($time);  // Decode since it was URI encoded

    include "connect.php";
    $cmd = "SELECT * FROM `reviews` WHERE `date` < ? ORDER BY `date` DESC LIMIT 10";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$time]);

    $arr = [];
    while ($row = $stmt->fetch()) {

        // Recreate Associative Array, was having issues when I didn't do this
        // JS is expecting object with below fields
        // {username: user, title: review title, msg: review body, score: review score, 
        //  date: date review posted, img: img path, pfp: pfp_path, id: reviewID}

        $pfp_path = get_pfp_path($row["username"]);  // Use iamge handler function
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
    }
    sleep(1); // Add a little bit of a delay
    echo json_encode($arr);
}
