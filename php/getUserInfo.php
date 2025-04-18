<?php
session_start();
include "connect.php";

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$username = $_SESSION['username'];
$stmt = $dbh->prepare("SELECT email FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
if ($user) {
    echo json_encode([
        'email' => $user['email']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}
?> 