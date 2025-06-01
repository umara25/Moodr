<?php 
/**
 * Main Dashboard/Index Page
 * The main landing page for Moodr application
 * Displays announcements and provides navigation to other sections
 * Includes role-based content and styling for regular users vs admins
 */
session_start();
error_reporting(0); 
?>

<!doctype html>
<!--
This is the splash page which users will first be greeted with. It holds announcements
and important information about the club.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr</title>    
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/hamburger.css">
    <script src="../js/utils/socialButtons.js"></script>
    <script src="../js/utils/nav.js"></script>
    <?php
    // Load appropriate JavaScript based on user role and login status
    if (isset($_SESSION["username"])) {
        if ($_SESSION["role"] === "admin") {
            // Admin-specific scripts for post management and styling
            echo "<script src='../js/styles/indexStyleAdmin.js'></script>";
            echo "<script src='../js/styles/indexStyleRefresher.js'></script>";
            echo "<script src='../js/posts/postListenerAdmin.js'></script>";
        } else {
            // Regular user scripts
            echo "<script src='../js/styles/indexStyle.js'></script>";
            echo "<script src='../js/posts/scrollIndex.js'></script>";
        }
    } else {
        // Guest user scripts
        echo "<script src='../js/posts/scrollIndex.js'></script>";
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
                if ($loggedIn) {
                    if ($_SESSION["role"] === "admin") {
                        echo " A d m i n";
                    }
                }
                ?>
            </p>
            <!-- Hamburger nav -->
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

            <div id="about-us">
                <h1>About Us</h1>
                <p>Mac's very own music listening community! Join us for weekly listening parties, album discussions,
                    record store runs, concert outings, and more!</p>

                <p>Want to make an <i>Album Suggestion</i>? <a
                        href="https://docs.google.com/forms/u/0/d/e/1FAIpQLSeAWZ3hKneWsGEg7usLBhnX5lvzuHhFQyhzgqokE-0nKnnSUA/viewform?pli=1"
                        target="_blank">Click
                        Here!</a></p>

                <div id="social-icons">
                    <input type="image" src="../images/insta_icon.png" id="insta-button" alt="Our Instagram Page!" width="20" height="20">
                    <input type="image" src="../images/discord_icon.png" id="discord-button" alt="Our Discord Server!" width="20"
                        height="20">
                </div>
            </div>

            <?php // If admin, they can post.
            if ($loggedIn) {
                if ($_SESSION["role"] === "admin") {
                    ?>
                    <div id="make-post">
                        <h1>Make a Post</h1>
                        <div class="make-post-container">                            <form id="make-post-form" action="posthandler.php" method="POST">
                                <label for="title">Title:</label>
                                <input type="text" id="title" name="title" placeholder="Enter post title..." required>
                                <label for="msg">Message:</label>
                                <textarea id="msg" name="msg" placeholder="Enter your message..." rows="5"
                                    required></textarea>
                                <button id="submit" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>


            <div id="announcments">
                <h1>Latest Announcements</h1>
                <div id="posts">
                    <?php
                    include "imageHandler.php"; // Using get_pfp_path()
                    
                    // Get the latest announcements from the database
                    $cmd = "SELECT * FROM announcements ORDER BY date DESC LIMIT 5";
                    $stmt = $dbh->prepare($cmd);
                    $success = $stmt->execute();
                    if (!$success) {
                        echo "Error: Failed to retrieve announcements from database.";
                    }

                    $rendered_posts = []; // Keep track of post ids that have been rendered
                    
                    while ($row = $stmt->fetch()) {
                        array_push($rendered_posts, $row["postId"]); // Push postID to array
                        ?>
                        <span class="post">
                            <?php
                            $pfp_path = get_pfp_path($row["username"]);
                            echo "<div class = 'post-pfp'>";
                            if (file_exists($pfp_path)) {
                                echo "<img src = $pfp_path>"; // pfp exists in directory
                            } else {
                                echo "<img src = '../images/defaultpfp.jpg'>"; // Default pfp
                            }
                            echo "</div>";
                            ?>
                            <div class="textbox">
                                <div class="post-title">
                                    <p><b><?= $row["username"] ?> - <?= $row["title"] ?> <span
                                                class='timestamp'><?= $row["date"] ?></span></b></p>
                                </div>
                                <div class="post-text">
                                    <p><?= $row["message"] ?></p>
                                </div>
                            </div>
                            <?php // If admin, they delete posts.
                                if ($_SESSION["role"] === "admin") {
                                    ?>
                                <div class="trash-icon" id="<?= $row["postId"] ?>">
                                    <img src="../images/trashicon.png" width="20px" height="20px">
                                </div>
                                <?php
                                }
                                $_SESSION["rendered_posts"] = $rendered_posts; // Store rendered post ID's in session storage
                                ?>

                        </span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="footer"> </div>
    </div>

</body>

</html>