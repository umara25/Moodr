<!doctype html>
<!--
This is the splash page which users will first be greeted with. It holds announcements and important information about the club.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Moodr</title>
    <script src="../js/postlistener.js"></script>
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

            <?php // If admin, they can post.
            if ($loggedIn) {
                if ($_SESSION["role"] === "admin") {
                    ?>
                    <div id="make-post">
                        <h1>Make a Post</h1>
                        <div class="make-post-container">

                            <form id="make-post-form" action="posthandler.php" method="POST">
                                <label for="post-title">Title:</label>
                                <input type="text" id="post-title" name="post-title" placeholder="Enter post title..." required>
                                <label for="post-message">Message:</label>
                                <textarea id="post-message" name="post-message" placeholder="Enter your message..." rows="5"
                                    required></textarea>
                                <button type="submit">Submit</button>
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
                    // Get the latest announcements from the database
                    $cmd = "SELECT * FROM announcements ORDER BY date DESC";
                    $stmt = $dbh->prepare($cmd);
                    $success = $stmt->execute();
                    if (!$success) {
                        echo "Error: Failed to retrieve announcements from database.";
                    }

                    while ($row = $stmt->fetch()) {
                        ?>
                        <span class="post">
                            <img src="../images/defaultpfp.jpg" width="75px" height="75px">
                            <div class="textbox">
                                <p><b><?= $row["username"] ?> - <?= $row["title"] ?> <span
                                            class='timestamp'><?= $row["date"] ?></span></b></p>
                                <p><?= $row["message"] ?></p>
                            </div>
                            <?php // If admin, they delete posts.
                                if ($_SESSION["role"] === "admin") {
                                    ?>
                                <div class="trash-icon" id="<?= $row["postId"] ?>">
                                    <img src="../images/trashicon.png" width="20px" height="20px">
                                </div>
                            <?php } ?>

                        </span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="footer"> </div>
    </div>

</body>

</html>