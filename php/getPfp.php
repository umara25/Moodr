<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit();
}

$username = $_SESSION['username'];

$cmd = "SELECT pfp FROM users WHERE username = ?";
$stmt = $dbh->prepare($cmd);
$stmt->execute([$username]);

if ($row = $stmt->fetch()) {
    if ($row["pfp"]) {
        header("Content-Type: image/jpeg");
        echo $row["pfp"];
        exit();
    }
}

readfile("../images/defaultpfp.jpg");
