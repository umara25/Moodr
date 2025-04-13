<?php session_start();?>
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
    <link rel="stylesheet" href="../css/usermanagment.css">
    <script src="../js/userManagment.js"></script>
</head>

<body>
    <?php
    include "connect.php";

    // Checks if there is an Active Session
    if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
        //Sends you back to login
        session_destroy();
        header('Location: login.php');
        exit;
    }

    if($_SESSION["role"]==="admin"){
        $cmd = "SELECT * FROM users";
        $stmt = $dbh->prepare($cmd);
        $stmt->execute();
    }
    
    ?>
    <div id="container">
        <div id="header">

            <p id="moodr">M o o d r <?php if ($_SESSION["role"] === "admin") {
                echo " A d m i n";
            } ?></p>
            <div class="nav-links">
                <a href="index.php" class="nav">Dashboard</a>
                <a href="calendar.php" class="nav">Calendar</a>
                <a href="reviews.php" class="nav">Reviews</a>
                <a href="usermanagment.php" class="nav">User Managment</a>
                <a href="myprofile.php" class="nav">My Profile</a>
                <a href="logouthandler.php" class="nav">Log out</a>
            </div>

        </div>
        <div id="content">

            <div id="table">
                <h1>User Management</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($_SESSION["role"]==="admin"){
                            while ($user = $stmt->fetch()) {
                        ?>
                            <tr class="user">
                                <td><?= $user["username"] ?></td>
                                <td><?= $user["email"] ?></td>
                                <td><?= $user["role"] ?></td>
                                <td class="editCol">
                                    <button class="ban-user" id="<?= $user['username'] ?>">BAN USER</button>
                                    <button class="make-admin" id="<?= $user['username'] ?>">Make Admin</button>
                                    <button class="delete-user" id="<?= $user['username'] ?>">Delete</button>
                                </td>
                            </tr>
                        <?php }} ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>