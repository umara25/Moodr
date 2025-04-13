<?php
session_start();
?>

<!doctype html>
<!--
This is the Admin Permissions Page, only accessible by admins.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - Admin Permission</title>
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/adminperms.css">
    <script src = "../js/storeStyle.js"></script>
</head>

<body>
    <?php

    // Checks if there is an Active Session and they are admin
    if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
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
                <a href='usermanagment.php' class='nav'>User Managment</a>
                <a href="myprofile.php" class="nav">My Profile</a>
                <a href="logouthandler.php" class="nav">Log out</a>
            </div>


        </div>
        <div id="content">
            <h1>Admin Permissions</h1>
            <div id = "styleCreate"> 
                <!-- ADD UPDATING STYLES, USING UPDATE SQL -->
                 <!-- ADD DELETING STYLES -->
                <h1>Create Custom Styles</h1>
                    <input id = "primary" type = "color">
                    <input id = "secondary" type = "color">
                    <input id = "text" type = "color">
                    <input id = "textbox" type = "color">
                    <input id = "confirmStyle" type = "button" value = "submit">
                    <p id = "styleResult"></p>
            </div>
            <!-- USER MANAGMENT TODO -->
        </div>
    </div>

</body>
</html>