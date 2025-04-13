<?php session_start();?>
<!doctype html>
<!--
This is the Calendar Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - Calendar</title>
    <link rel="stylesheet" href="../css/index.css">
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
            if($loggedIn){
                if ($_SESSION["role"] === "admin") {
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
            CAL TODO
        </div>
    </div>

</body>
</html>