<?php
/** 
 * Make a Post Processing
 * Checks if user is admin, then checks relevent parameters
 * to make a public post. 
 */
session_start();
include "connect.php";
include "imageHandler.php"; // Using get_pfp_path()
date_default_timezone_set('America/New_York'); // Removes time-zone errors when updating date

$loggedIn = false;

// Checks if there is an Active Session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}


if ($loggedIn) {
    if ($_SESSION["role"] === "admin") {
        $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_SPECIAL_CHARS);
        $message = filter_input(INPUT_POST, "msg", FILTER_SANITIZE_SPECIAL_CHARS);
    }
} else {
    session_destroy();
    header('Location: login.php'); // redirects logged out session to the login page.
    exit();
}


$cmd = "INSERT INTO announcements (username, title, message) VALUES (?,?,?)";
$stmt = $dbh->prepare($cmd);
$success = $stmt->execute([$_SESSION["username"], $title, $message]);
if (!$success) {
    echo "Error: Failed to insert into database.";
}
// Get the ID of the newly inserted post
$postId = $dbh->lastInsertId();

$pfp_path = get_pfp_path($_SESSION["username"]);
echo "<span class='post'><div class = 'post-pfp'>";
if (file_exists($pfp_path)) {
    echo "<img src = $pfp_path>"; // pfp exists in directory
} else {
    echo "<img src = '../images/defaultpfp.jpg'>"; // Default pfp
}
echo "</div>";

echo "<div class='textbox'><div class='post-title'>";
echo "<p><b>$_SESSION[username] - $title </b></p></div>";
echo "<div class='post-text'><p>$message</p></div></div>";
echo "<div class='trash-icon' id='$postId'>";
echo "<img src='../images/trashicon.png' width='20px' height='20px'>";
echo "</div></div></span>";