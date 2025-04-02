<!doctype html>
<!--
This is the USER Main Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr</title>
    <link rel="stylesheet" href="../css/mainpg.css">
    <script src="../js/mainpgButtons.js"></script>
</head>

<body>
    <?php
    session_start();
    include "connect.php";

    // Checks if there is an Active Session
    if (!isset($_SESSION["username"])) {
        //Sends you back to login
        session_destroy();
        header('Location: login.php');
        exit;
    }
    ?>
    <div id="container">
        <div id="header">

            <p class="nav">M o o d r <?php if ($_SESSION["role"] === "admin") {
                                            echo " A d m i n";
                                        } ?></p>
            <div class="nav-links">
                <button id="dashb-btn" class="nav">Dashboard</button>
                <button id="cal-btn" class="nav">Calendar</button>
                <button id="review-btn" class="nav">Reviews</button>
                <?php // If admin, they will have a user management button.
                if ($_SESSION["role"] === "admin") {
                    echo "<button id='usermang-btn' class='nav'>User Managment</button>";
                }
                ?>
                <button id="myprofile-btn" class="nav">My Profile</button>
                <button id="logout-btn" class="nav">Log out</button>
            </div>

        </div>
        <div id="content">
            <div id="user-intro">
                <h1>Welcome <?= $_SESSION["username"] ?>!</h1>
                <p>What's on the agenda for today?</p>
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