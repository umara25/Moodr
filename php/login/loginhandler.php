<?php

/** 
 * Login Authentication Handler
 * Processes login form submissions and validates user credentials
 * Sets up user session on successful authentication
 * Handles account status checking (active/inactive)
 */

session_start();
include "connect.php";

// Sanitize input data to prevent XSS attacks
$username = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, "password");

// Validate that both username and password were provided
if ($username !== null && $password !== null && $username !== false) {

    // Check if user exists in database
    $cmd = "SELECT * FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute([$username]);

    // Process login if user found
    if ($row = $stmt->fetch()) {

        // Verify password using password_verify for security
        if (password_verify($password, $row["password"])) {
            
            // Check if account is banned/inactive
            if ($row['status'] === 'inactive') {
                // Set session flag for banned account
                $_SESSION["inactive"] = true;
                // Redirect back to login with error
                header('Location: login.php');
                exit;
            } else {
                // Successful login - set up user session
                $_SESSION["username"] = $row["username"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["bio"] = $row["bio"];

                // Load user's custom style preferences
                $style = get_style($row["styleID"]);
                if ($style !== false) {
                    $_SESSION["style"] = $style;
                } else {
                    $_SESSION["style"] = default_style();
                }

                // Redirect to main dashboard
                header('Location: index.php');
                exit;
            }
        } else {
            // Invalid password - redirect to login with error
            $_SESSION["loginFail"] = true;
            header('Location: login.php');
            exit;
        }
    } else {
        // User not found - redirect to login with error
        $_SESSION["loginFail"] = true;
        header('Location: login.php');
        exit;
    }
}

/** 
 * Takes a username and gets the style the user has set 
 * Returns an associative array of hexcodes for each style attribute
 * Returns false if some error occurs with selecting
 */
function get_style($styleID)
{
    include "connect.php";

    // Select style from styles table
    $cmd = "SELECT * FROM styles WHERE `styleID` = ?";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$styleID]);

    if ($row = $stmt->fetch()) {
        $AA = [
            "primary" => "#" . $row["primary_colour"],
            "secondary" => "#" . $row["secondary_colour"],
            "textbox" => "#" . $row["textbox_colour"],
            "text" => "#" . $row["text_colour"]
        ];  // Construct AA of the styles, add # to front so it can be used as hexcode 
        return $AA;
    } else {
        return false;
    }
}


/** 
 * Return Associative Array storing default style
 */
function default_style()
{
    $AA = [
        "primary" => "#2b3137",
        "secondary" => "#24292e",
        "textbox" => "#f5f5f5",
        "text" => "#181e23"
    ];
    return $AA;
}
