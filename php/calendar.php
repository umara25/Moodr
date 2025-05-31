<?php 
/** 
 * Calendar Page
 * Displays a calendar interface for users to view their mood entries
 * Includes navigation and user authentication
 */
session_start();
?>
<!doctype html>
<!--
This is the Calendar Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - Calendar</title>
    <link rel="stylesheet" href="../css/calendar.css">
    <link rel = "stylesheet" href = "../css/hamburger.css">
    <script src="../js/nav.js"></script>
    <?php
    // If user is logged in, apply their custom styling
    if(isset($_SESSION["username"])){
        echo "<script src='../js/calenderStyle.js'></script>";
    }
    ?>
</head>

<body>
    <?php
    include "connect.php";
    $loggedIn = false;

    // Check if there is an active user session
    if (isset($_SESSION["username"])) {
        $loggedIn = true;
        include "statusCheck.php";
        status_check($_SESSION["username"], $_SESSION["role"]); // Verify user status and permissions
    }

    ?>
    <div id="container">
        <div id="header">

            <p id="moodr">M o o d r 
            <?php 
            // Display admin indicator if user is admin
            if($loggedIn){
                if ($_SESSION["role"] === "admin") {
                    echo " A d m i n";
                } 
            }
            ?>
            </p>
             <!-- Hamburger navigation menu for mobile -->
             <div id="hamburger">
                <img src="../images/hamburger.png">
                <div id="hamburger-content">
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
            <div id="calender">
                <div class="calendar-container">
                    <h2>Monthly Calendar</h2>
                    <iframe src="https://calendar.google.com/calendar/embed?src=2457521e4fcf4cc28835fca83e2eac85013d65751c9d04469752959d9a238a31%40group.calendar.google.com&ctz=America%2FNew_York&showNav=1&showDate=1&showPrint=0&showTabs=1&showCalendars=0&showTz=1&mode=MONTH&wkst=1&outputType=EMBED" 
                            frameborder="0" 
                            scrolling="no">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

