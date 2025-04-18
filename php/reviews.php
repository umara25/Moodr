<?php session_start();
error_reporting(0);

$loggedIn = false;

// Checks if there is an Active Session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
    include "statusCheck.php";
    status_check($_SESSION["username"], $_SESSION["role"]); // Check users status
}
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
    <script src = "../js/nav.js"></script>

    <?php
    if ($loggedIn) {
        if ($_SESSION["role"] === "admin") {
            //Include create review JS if admin
            echo "<script src = '../js/reviewListener.js'></script>";
        }
    }
    ?>
</head>

<body>
    <div id="container">
        <div id="header">

            <p id="moodr">M o o d r
                <?php
                if ($loggedIn) {
                    if ($_SESSION["role"] === "admin") {
                        echo " A d m i n";
                    }
                }
                ?>
            </p>

            <!-- Hamburger nav -->
            <div id = "hamburger">
                <img src="../images/hamburger.png">
                <div id = "hamburger-content">
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

                    <div id="make-post-js">
                        <input id='make-post-button' type="button" value="Click to write an album review">
                        <!-- <img id = "make-post-img" src = "../images/write.png"> -->
                    </div>

                    <div id="make-post">

                        <div id="close-post">
                            <img id="close-post-img" src="../images/close.png">
                        </div>

                        <h1>Write an Album Review</h1>

                        <div class="make-post-container">

                            <form id="make-review-form" method="POST" enctype="multipart/form-data">
                                <label for="review-title">Title:</label>
                                <input type="text" id="review-title" name="post-title" placeholder="Enter review title... (30 chars max)" required maxlength="30">

                                <label id="file-label" for="album-cover" class="pfp-btn">Choose Image (.png, .jpg, .jpeg)</label>
                                <input type="file" id="album-cover" accept="image/png, image/jpg, image/jpeg">

                                <label for="review-message">Review:</label>
                                <textarea id="review-message" name="post-message" placeholder="Enter your review... (400 chars max)" rows="5"
                                    maxlength="400" required></textarea>

                                <label for="review-score" id="score-field">Score: 0 / 10</label>
                                <input id="review-score" type="range" min="0" max="10" placeholder="Score from 0-10" value="0" step="0.5">

                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
            <?php
                } else {  // Not admin 
                    echo "<h1 id = 'review-header'>Reviews</h1>";
                }
            } else { // Not logged in 
                echo "<h1 id = 'review-header'>Reviews</h1>";
            }
            ?>
            <div id="reviews">

                <div id='review-fail' class='error'></div>

                <!-- <div class = "review"> 
                    <div class = "review-pfp"> 
                        <img src = "../images/defaultpfp.jpg">
                    </div>
                    <div class = "triangle"></div>

                    <div class = "review-content"> 

                        <div class = "review-title"> 
                            <h1> MY FIRST REVIEW - Username <span class = 'timestamp'> 2025-04-15 </span></h1> 
                        </div>

                        <div class = "review-body"> 

                            <div class = "review-img"> 
                                <img src = "../images/write.png"> 
                            </div>
                            <div class = "review-text"> 
                                <p>
                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                                    Vitae corrupti minima voluptate laboriosam voluptatibus aliquid, 
                                    enim facere ipsum hic? Eos, natus enim! Odit, explicabo! 
                                    Cupiditate molestias reiciendis velit doloremque quod!
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Exercitationem, sequi doloribus, ducimus animi ex asperiores cumque aperiam quis iste labore adipisci, harum perferendis et amet? Minus delectus impedit harum officiis.
                                </p>
                                <p>Score: 10/10</p>
                            </div>

                        </div>

                    </div>
            
                </div>

                <div class = "review"> 
                    <div class = "review-pfp"> 
                        <img src = "../images/defaultpfp.jpg">
                    </div>
                    <div class = "triangle"></div>

                    <div class = "review-content"> 

                        <div class = "review-title"> 
                            <h1> MY FIRST REVIEW - Username </h1>
                            <img class = "trash-icon" src = "../images/trashicon.png">
                        </div>

                        <div class = "review-body"> 

                            <div class = "review-img"> 
                                <img src = "../ReviewImgs/3wyhvuNX1744847962.jpg"> 
                            </div>
                            <div class = "review-text"> 
                                <p>
                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                                    Vitae corrupti minima voluptate laboriosam voluptatibus aliquid, 
                                    enim facere ipsum hic? Eos, natus enim! Odit, explicabo! 
                                    Cupiditate molestias reiciendis velit doloremque quod!
                                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. Exercitationem, sequi doloribus, ducimus animi ex asperiores cumque aperiam quis iste labore adipisci, harum perferendis et amet? Minus delectus impedit harum officiis.
                                </p>
                            </div>

                        </div>

                    </div>
            
                </div> -->

                <?php
                include "connect.php";

                $cmd = "SELECT * FROM reviews ORDER BY `date` DESC LIMIT 10";
                $stmt = $dbh->prepare($cmd);
                $succ = $stmt->execute();

                while ($row = $stmt->fetch()) {
                    //Grab each review 

                    echo "<div id = '$row[reviewID]' class = 'review'>";

                    // TEMPORARY PFP, REPLACE LATER
                    echo "<div class = 'review-pfp'> 
                            <img src = '../images/defaultpfp.jpg'> 
                        </div>"; // Temporary PFP, REPLACE WITH ACTUAL LATER

                    echo "<div class = 'triangle'></div>";
                    echo "<div class = 'review-content'>";

                    echo "<div class = 'review-title'>
                                        <h1> $row[title]  -  $row[username]
                                        <span class = 'timestamp'>$row[date]</span>
                                        </h1>";
                    if ($loggedIn) {
                        if ($_SESSION["role"] === "admin") {  // Echo delete button if admin
                            echo "<img class = 'trash-icon' src = '../images/trashicon.png'>
                                  </div>";
                        } else {
                            echo "</div>";
                        }
                    } else {
                        echo "</div>";
                    }

                    echo "<div class = 'review-body'>";

                    if ($row["img_path"] !== NULL && file_exists($row["img_path"])) {
                        // Image was uploaded and exists in directory
                        echo "<div class = 'review-img'>
                                            <img src =" . $row["img_path"] . ">
                                            </div>";
                    }

                    echo "<div class = 'review-text'>
                                        <p>$row[text_body]</p>
                                        <p>Score: $row[score]/10</p>
                                    </div>";

                    echo "</div></div></div>";
                }
                // echo "<h1 class = 'error'>FAILED TO RETRIEVE REVIEWS, TRY AGAIN LATER</h1>";
                ?>

            </div>

        </div>
    </div>

</body>

</html>