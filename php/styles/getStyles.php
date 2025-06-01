<?php
/**
 * Get Styles Handler
 * Retrieves all available custom styles from database
 * Returns style data as JSON for frontend styling
 */

session_start();
header('Content-Type:application/json');

include "connect.php";

$loggedIn = false;

// Check if there is an active user session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

if ($loggedIn) {
    // Retrieve all custom styles from database
    $cmd = "SELECT * FROM styles";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute();
    
    $styles = [];
    
    // Format each style record for JSON response
    while ($row = $stmt->fetch()) {
        $style = [
            "styleID" => $row["styleID"],
            "primary" => "#" . $row["primary_colour"],     // Primary color theme
            "secondary" => "#" . $row["secondary_colour"], // Secondary color theme
            "textbox" => "#" . $row["textbox_colour"],     // Input/textbox background
            "text" => "#" . $row["text_colour"],           // Text color
        ];
        $styles[] = $style;
    }
    
    // Return styles as JSON
    echo json_encode($styles);
} else {
    // Return error for unauthorized access
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
}