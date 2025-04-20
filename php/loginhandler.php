<?php

/** 
 * Password verification 
 * Checks if user exists in database, and if they do 
 * checks if the password matches
 */
session_start();
include "connect.php";
$username = filter_input(INPUT_POST, "user", FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, "password");


if ($username !== null && $password !== null && $username !== false) {

    /** 
     * Prepare SELECT command to see if they exist
     */
    $cmd = "SELECT * FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $success = $stmt->execute([$username]);

    //Check if user found
    if ($row = $stmt->fetch()) {

        if (password_verify($password, $row["password"])) {
            if ($row['status'] === 'inactive') {
                // Account was banned
                //Set session as failed login
                $_SESSION["inactive"] = true;
                //Sends you back to login
                header('Location: login.php');
                exit;
            } else {
                //Access granted
                //Set SESSION variables
                $_SESSION["username"] = $row["username"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["bio"] = $row["bio"];

                $style = get_style($row["styleID"]);
                if ($style !== false) {
                    $_SESSION["style"] = $style;
                } else {
                    $_SESSION["style"] = default_style();
                }

                header('Location: index.php');
                exit;
            }
        } else {
            //Set session as failed login
            $_SESSION["loginFail"] = true;
            //Sends you back to login
            header('Location: login.php');
            exit;
        }
    } else { //User does not exist 

        //Set session as failed login
        $_SESSION["loginFail"] = true;
        //Sends you back to login
        header('Location: login.php');
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
        "textbox" => "#FFFFFF",
        "text" => "#000000"
    ];
    return $AA;
}
