<!doctype html>
<!--
This is the splash page which users will first be greeted with. It holds announcements and important information about the club.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr</title>
    <link rel="stylesheet" href="../css/index.css">
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

            <p id="moodr">M o o d r
                <?php
                if ($loggedIn) {
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
            <div id="user-intro">
                <?php
                if (isset($_SESSION['username'])) {
                    ?>
                    <h1>Welcome <?= $_SESSION['username'] ?>!</h1>
                    <p>What are some of your favourite albums?</p>
                    <?php
                } else {
                    echo "<h1>Welcome to Mood FM!</h1>";
                    echo "<p>What's on the agenda for today?</p>";
                }
                ?>


            </div>
            <div id="announcments">
                <h1>Latest Announcements</h1>
                <div class="post">
                    <img src="../images/defaultpfp.jpg" width="75px" height="75px">
                    <div class="textbox">
                        <p><b>Username -  Title</b></p>
                        <p>Mood FM Study Session - April 4th @ 6 PM</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="footer"> </div>
    </div>

</body>

</html>