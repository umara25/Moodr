<?php 

/** 
 * User Status Verification Function
 * Validates that the current user session matches database records
 * Checks both user role and account status (active/inactive)
 * Logs out users if their account has been banned or role changed
 */

function status_check($username,$role){ 
    include "connect.php";

    // Query database for current user role and status
    $cmd = "SELECT `role`,`status` FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $succ = $stmt->execute([$username]);

    if($row = $stmt->fetch()){ 
        // User found in database - verify session data matches
        
        if($row['role'] !==  $role || $row['status'] === 'inactive'){ 
            // Session role doesn't match database role OR account has been banned
            header('Location: login.php?status=1');  // Redirect to login with status flag
            session_destroy();                       // Clear invalid session
            exit;
        }          
    }
}
