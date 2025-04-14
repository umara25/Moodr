<?php session_start();
error_reporting(0);
?>
<!doctype html>
<!--
This is the Review Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - Reviews</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/reviews.css">

    <?php if($_SESSION["role"] === "admin"){ 
        //Include create review JS if admin
        echo "<script src = '../js/reviewListener.js'></script>";
    }
    ?>
</head>

<body>
    <?php
    include "connect.php";
    $loggedIn = false;

    // Checks if there is an Active Session
    if (isset($_SESSION["username"])) {
        $loggedIn = true;
    }
    ?>
    <div id="container">
        <div id="header">

            <p id="moodr">M o o d r
                <?php
                if ($loggedIn){
                    if($_SESSION["role"] === "admin") {
                    echo " A d m i n";
                    }
                 }
                ?>
            </p>
            <div class="nav-links">
                <a href="index.php" class="nav">Dashboard</a>
                <a href="calendar.php" class="nav">Calendar</a>
                <a href="reviews.php" class="nav">Reviews</a>
                <?php // If admin, they will have a user management button.
                if ($loggedIn) {
                    if ($_SESSION["role"] === "admin") {
                        echo "<a href='usermanagment.php' class='nav'>User Managment</a>";
                    }
                }
                if (!$loggedIn) {
                    echo "<a href='login.php' class='nav'>Log in</a>";
                } else {
                    echo "<a href='myprofile.php' class='nav'>My Profile</a>";
                    echo "<a href='logouthandler.php' class='nav'>Log out</a>";
                }
                ?>

            </div>

        </div>
        <div id="content">
            <?php 
            if ($loggedIn) {
                if ($_SESSION["role"] === "admin") {
                    ?>
                    <div id="make-post">
                        <h1>Write a Review</h1>
                        <div class="make-post-container">

                            <form id="make-review-form" method="POST" enctype="multipart/form-data">
                                <label for="review-title">Title:</label>
                                <input type="text" id="review-title" name="post-title" placeholder="Enter review title... (30 chars max)" required maxlength = "30">

                                <label for="album-cover" class="pfp-btn">Choose Image</label>
                                <input type = "file" id = "album-cover" accept="image/*">

                                <label for="review-message">Review:</label>
                                <textarea id="review-message" name="post-message" placeholder="Enter your review..." rows="5"
                                    required></textarea>

                                <label for = "review-score" id = "score-field">Score: 0 / 10</label> 
                                <input id ="review-score" type = "range" min = "0" max = "10" placeholder = "Score from 0-10" value ="0" step = "0.5" >

                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            <div id = "reviews"> 

                <?php 

                    $cmd = "SELECT * FROM reviews ORDER BY date desc";
                    $stmt = $dbh->prepare($cmd); 
                    $succ = $stmt->execute();

                    while($row = $stmt->fetch()){ 
                        //Grab each review 

                        

                    }

                ?>

            </div>

        </div>
    </div>

</body>

</html>