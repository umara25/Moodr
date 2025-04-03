<?php
session_start();
include "connect.php";

if (isset($_POST['bio']) && isset($_SESSION['username'])) {
    $bio = $_POST['bio'];
    $username = $_SESSION['username'];

    $stmt = $dbh->prepare("UPDATE users SET bio = ? WHERE username = ?");
    $stmt->execute([$bio, $username]);

    $_SESSION['bio'] = $bio;
}

header("Location: myprofile.php");
exit();
