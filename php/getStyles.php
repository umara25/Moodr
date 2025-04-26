<?php
session_start();
header('Content-Type:application/json');

include "connect.php";

$loggedIn = false;
// Checks if there is an Active Session
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

if ($loggedIn) {
    // Get all styles from database
    $cmd = "SELECT * FROM styles";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute();
    
    $styles = [];
    
    while ($row = $stmt->fetch()) {
        $style = [
            "styleID" => $row["styleID"],
            "primary" => "#" . $row["primary_colour"],
            "secondary" => "#" . $row["secondary_colour"],
            "textbox" => "#" . $row["textbox_colour"],
            "text" => "#" . $row["text_colour"],
        ];
        $styles[] = $style;
    }
    
    echo json_encode($styles);
} else {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
} 