<!doctype html>
<!--
This is the User Managment Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - User Managment</title>
    <link rel="stylesheet" href="../css/index.css">
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

            <p id="moodr">M o o d r <?php if ($_SESSION["role"] === "admin") {echo " A d m i n";} ?></p>
            <div class="nav-links">
                <a href="index.php" class="nav">Dashboard</a>
                <a href="calendar.php" class="nav">Calendar</a>
                <a href="reviews.php" class="nav">Reviews</a>
                <?php // If admin, they will have a user management button.
                if ($_SESSION["role"] === "admin") {
                    echo "<a href='usermanagment.php' class='nav'>User Managment</a>";
                }
                ?>
                <a href="myprofile.php" class="nav">My Profile</a>
                <a href="logouthandler.php" class="nav">Log out</a>
            </div>

        </div>
        <div id="content">
            USER MANAGMENT TODO
        </div>
    </div>

</body>
</html>