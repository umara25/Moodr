<?php
/**
 * Get User Info Handler
 * Retrieves basic user information for the logged-in user
 * Returns user data as JSON for frontend consumption
 */

session_start();
include "connect.php";

// Ensure user is logged in before retrieving info
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit();
}

$username = $_SESSION['username'];

// Get user's email from database
$stmt = $dbh->prepare("SELECT email FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

header('Content-Type: application/json');

if ($user) {
    // Return user information as JSON
    echo json_encode([
        'email' => $user['email']
    ]);
} else {
    // Return error if user not found
    echo json_encode(['error' => 'User not found']);
}
?>