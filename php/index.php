<!doctype html>
<!--
This is the splash page which users will first be greeted with. It holds announcements and important information about the club.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr</title>
    <link rel="stylesheet" href="../css/mainpg.css">
    <script src="../js/index.js"></script>
</head>

<body>
    <?php
    session_start();
    include "connect.php";
    $loggedIn = false;

    // Checks if there is an Active Session
    if (isset($_SESSION["username"])) {
        $loggedIn = true;
    }
    ?>
    <div id="container">
        <div id="header">

            <p class="nav">M o o d r
                <?php
                if ($loggedIn) {
                    if ($_SESSION["role"] === "admin") {
                        echo " A d m i n";
                    }
                }
                ?>
            </p>
            <div class="nav-links">
                <button id="dashb-btn" class="nav">Dashboard</button>
                <button id="cal-btn" class="nav">Calendar</button>
                <button id="review-btn" class="nav">Reviews</button>
                <?php // If admin, they will have a user management button.
                if ($loggedIn) {
                    if ($_SESSION["role"] === "admin") {
                        echo "<button id='usermang-btn' class='nav'>User Managment</button>";
                    }
                }
                ?>
                <button id="myprofile-btn" class="nav">My Profile</button>
                <?php
                if (!isset($_SESSION["username"])) {
                    echo "<button id='loginpage-btn' class='nav'>Log in</button>";
                } else {
                    echo "<button id='loginpage-btn' class='nav'>Log out</button>";
                }
                ?>

            </div>

        </div>
        <div id="content">
            <div id="user-intro">
                <?php
                if (isset($_SESSION['username'])) {
                ?>
                    <h1>Welcome <?= $_SESSION['username'] ?>!</h1>
                    <p>What's on the agenda for today?</p>
                <?php
                } else {
                    echo "<h1>Welcome to Mood FM!</h1>";
                    echo "<p>What's on the agenda for today?</p>";
                }
                ?>


            </div>
            <div id="announcments">
                <h1>Latest Announcements</h1>
                <ul>
                    <li>Mood FM Exec Meeting – April 4th @ 6 PM</li>
                </ul>
            </div>
        </div>
        <div id="footer"> </div>
    </div>

</body>

</html>