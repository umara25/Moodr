<!doctype html>
<!--
This is the My Profile Page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr - My Profile</title>
    <link rel="stylesheet" href="../css/myprofile.css">
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
            <div id="profile-container">
                <div id="profile-left">
                    <?php if ($loggedIn): ?>
                        <img src="getPfp.php" id="profile-image">
                        <form id="pfp-form" action="uploadPfp.php" method="POST" enctype="multipart/form-data">
                            <label for="pfp-input" class="pfp-btn">Choose File</label>
                            <input type="file" name="pfp" id="pfp-input" accept="image/*">
                            <button type="submit" class="pfp-btn">Change Profile Picture</button>
                        </form>
                    <?php else: ?>
                        <div id="profile-image"></div>
                    <?php endif; ?>
                </div>

                <div id="profile-options">
                    <button class="profile-btn">Change Username</button>
                    <button class="profile-btn">Change Password</button>
                    <button class="profile-btn">Change Personal Info</button>
                    <button class="profile-btn-delete">Delete Profile</button>
                    <form id="bio-form" method="POST" action="updateBio.php">
                        <div id="bio-box">
                            <textarea id="bio-textarea" name="bio" placeholder="Write a short bio..."><?php
                                  echo isset($_SESSION['bio']) ? htmlspecialchars($_SESSION['bio']) : '';
                                  ?></textarea>
                            <button type="submit" id="save-bio-btn">Save Bio</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</body>

</html>