<?php
session_start();
include "connect.php";

$loggedIn = false;
if (isset($_SESSION["username"])) {
    $loggedIn = true;
}

if ($loggedIn) {
    $styleID = filter_input(INPUT_POST, "styleID", FILTER_SANITIZE_SPECIAL_CHARS);
    $username = $_SESSION["username"];
    
    if (!$styleID) {
        echo json_encode(["success" => false, "message" => "Invalid style ID"]);
        exit();
    }
    
    $checkCmd = "SELECT * FROM styles WHERE styleID = ?";
    $checkStmt = $dbh->prepare($checkCmd);
    $checkSuccess = $checkStmt->execute([$styleID]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Style does not exist"]);
        exit();
    }
    
    $cmd = "UPDATE users SET styleID = ? WHERE username = ?";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute([$styleID, $username]);
    
    if ($success) {
        $styleCmd = "SELECT * FROM styles WHERE styleID = ?";
        $styleStmt = $dbh->prepare($styleCmd);
        $styleSuccess = $styleStmt->execute([$styleID]);
        
        if ($row = $styleStmt->fetch()) {
            $_SESSION["style"] = [
                "primary" => "#" . $row["primary_colour"],
                "secondary" => "#" . $row["secondary_colour"],
                "textbox" => "#" . $row["textbox_colour"],
                "text" => "#" . $row["text_colour"]
            ];
            
            echo json_encode(["success" => true, "message" => "Style updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to retrieve style details"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update style"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
} 