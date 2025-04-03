<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username']) || !isset($_FILES['pfp'])) {
    header("Location: myprofile.php");
    exit();
}

$username = $_SESSION['username'];
$imageData = file_get_contents($_FILES['pfp']['tmp_name']);

$cmd = "UPDATE users SET pfp = ? WHERE username = ?";
$stmt = $dbh->prepare($cmd);
$stmt->bindParam(1, $imageData, PDO::PARAM_LOB);
$stmt->bindParam(2, $username);
$stmt->execute();

header("Location: myprofile.php");
exit();
