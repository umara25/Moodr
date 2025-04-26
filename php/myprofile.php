<?php session_start();?>
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
    <link rel = "stylesheet" href = "../css/hamburger.css">
    <script src="../js/nav.js"></script>
    <script src="../js/changeusername.js"></script>
    <script src="../js/changepassword.js"></script>
    <script src="../js/changepersonalinfo.js"></script>
    <script src="../js/deleteprofile.js"></script>
    <script src="../js/profilePicture.js"></script>
    <script src="../js/stylePicker.js"></script>
    <?php
    //if user is logged in then apply their style
    if(isset($_SESSION["username"])){
        echo "<script src='../js/myProfileStyle.js'></script>";
    }
    ?>
</head>

<body>
    <?php
    include "connect.php";
    $loggedIn = false;
    $currentStyleID = "default";

    // Checks if there is an Active Session
    if (isset($_SESSION["username"])) {
        $loggedIn = true;
        include "statusCheck.php";
        status_check($_SESSION["username"], $_SESSION["role"]); // Check users status
        
        // Get usesr current style ID
        $cmd = "SELECT styleID FROM users WHERE username = ?";
        $stmt = $dbh->prepare($cmd);
        $stmt->execute([$_SESSION["username"]]);
        if ($row = $stmt->fetch()) {
            $currentStyleID = $row["styleID"];
        }
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
                            echo "<a href='usermanagment.php' class='nav'>Administration</a>";
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
                        echo "<a href='usermanagment.php' class='nav'>Administration</a>";
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
            <div id="profile">
                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div id="error-message">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                if (isset($_SESSION['success'])) {
                    echo '<div id="success-message">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>
                <div id="profile-container">
                    <div id="profile-left">
                        <?php if ($loggedIn): ?>
                            <img src="getPfp.php" id="profile-image">
                            <form id="pfp-form" action="uploadPfp.php" method="POST" enctype="multipart/form-data">
                                <label for="pfp-input" class="pfp-btn">Choose File</label>
                                <input type="file" name="pfp" id="pfp-input" accept="image/jpeg, image/png">
                                <button type="submit" id="pfp-submit-btn" class="pfp-btn">Save</button>
                            </form>
                        <?php else: ?>
                            <div id="profile-image"></div>
                        <?php endif; ?>
                    </div>

                    <div id="profile-options">
                        <button id="change-username-btn" class="profile-btn">Change Username</button>
                        <button id="change-password-btn" class="profile-btn">Change Password</button>
                        <button id="change-info-btn" class="profile-btn">Change Personal Info</button>
                        <button id="change-theme-btn" class="profile-btn">Change Theme</button>
                        <button id="delete-profile-btn" class="profile-btn-delete">Delete Profile</button>
                        <form id="bio-form" method="POST" action="updateBio.php">
                            <div id="bio-box">
                                <b>Write a short bio</b>
                                <textarea id="bio-textarea" name="bio" ><?php
                                    if (isset($_SESSION['bio'])) {
                                        echo htmlspecialchars($_SESSION['bio']);
                                    } else {
                                        echo '';
                                    }
                                    ?></textarea>
                                <button type="submit" id="save-bio-btn">Save Bio</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div id="theme-form" style="display: none;">
                    <div class="form-heading">Change Theme</div>
                    <form id="style-form">
                        <div class="form-group">
                            <label for="style-picker">Select Theme:</label>
                            <input type="hidden" id="current-style-id" value="<?= htmlspecialchars($currentStyleID) ?>">
                            <select id="style-picker" name="style-picker" class="form-input">
                            </select>
                        </div>
                        
                        <div id="style-preview">
                            <div>
                                <div id="preview-primary" class="color-preview"></div>
                                <span class="color-label">Primary</span>
                            </div>
                            <div>
                                <div id="preview-secondary" class="color-preview"></div>
                                <span class="color-label">Secondary</span>
                            </div>
                            <div>
                                <div id="preview-textbox" class="color-preview"></div>
                                <span class="color-label">Textbox</span>
                            </div>
                            <div>
                                <div id="preview-text" class="color-preview"></div>
                                <span class="color-label">Text</span>
                            </div>
                        </div>
                        
                        <div class="button-group">
                            <button type="button" id="cancel-theme" class="cancel-button">Cancel</button>
                            <button type="submit" class="save-button">Apply Theme</button>
                        </div>
                    </form>
                </div>
                
                <div id="delete-profile" class="popup">
                    <div class="popup-content">
                        <span class="close">&times;</span>
                        <h2>Delete Profile</h2>
                        <p class="warning-text">Warning: This action cannot be undone and all your data will be permanently deleted.</p>
                        
                        <form id="delete-profile-form" action="deleteProfile.php" method="POST">
                            <div class="form-group">
                                <label for="password">Enter Password:</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirmPassword">Confirm Password:</label>
                                <input type="password" id="confirmPassword" name="confirmPassword" required>
                                <div id="password-error" class="form-error"></div>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <input type="checkbox" id="confirmDelete" name="confirmDelete" required>
                                <label for="confirmDelete">I understand that my account and all related data will be permanently deleted and cannot be recovered.</label>
                                <div id="checkbox-error" class="form-error"></div>
                            </div>
                            
                            <div class="form-buttons">
                                <button type="button" id="cancel-delete" class="cancel-btn">Cancel</button>
                                <button type="submit" id="confirm-delete" class="delete-btn">Delete My Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</body>

</html>