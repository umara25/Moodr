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
            <div class="calendar-container">
                <h2>Team Calendar</h2>
                <p>This calendar displays team events and schedules. Updates made on the team's Google Calendar will appear here automatically.</p>
                <iframe src="https://calendar.google.com/calendar/embed?src=2457521e4fcf4cc28835fca83e2eac85013d65751c9d04469752959d9a238a31%40group.calendar.google.com&ctz=America%2FNew_York&showNav=1&showDate=1&showPrint=0&showTabs=1&showCalendars=0&showTz=1&mode=MONTH&wkst=1&outputType=EMBED" 
                        style="border: 0" 
                        width="100%" 
                        height="600" 
                        frameborder="0" 
                        scrolling="no">
                </iframe>
                <p class="calendar-note">
                    <p>To add events to this calendar, team members with access to the Google account can update it from their phone or computer.</p>
                </p>
            </div>
        </div>
    </div>

</body>
</html>