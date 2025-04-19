<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit();
}

$username = $_SESSION['username'];

$cmd = "SELECT pfp_path FROM users WHERE username = ?";
$stmt = $dbh->prepare($cmd);
$stmt->execute([$username]);

if ($row = $stmt->fetch()) {
    if ($row["pfp_path"] && file_exists($row["pfp_path"])) {
        $file_extension = pathinfo($row["pfp_path"], PATHINFO_EXTENSION);
        
        switch($file_extension) {
            case 'jpg':
            case 'jpeg':
                header("Content-Type: image/jpeg");
                break;
            case 'png':
                header("Content-Type: image/png");
                break;
            default:
                header("Content-Type: image/jpeg");
        }
        
        readfile($row["pfp_path"]);
        exit();
    }
}

readfile("../images/defaultpfp.jpg");
