<?php 

/** 
 * Takes username and role from $_SESSION and checks 
 * the database to see if role matches and if account is still valid
 */
function status_check($username,$role){ 
    include "connect.php";

    $cmd = "SELECT `role`,`status` FROM users WHERE `username` = ?";
    $stmt = $dbh->prepare($cmd);
    $succ = $stmt->execute([$username]);

    if($row = $stmt->fetch()){ 
        // Able to select for a row 

        if($row['role'] !== $role){ 
            // Database role doesn't match session role 
            header('Location: login.php?status=1');  // Send back to login with status = 1
            session_destroy();                       // Destroy session
        }          
        if($row['status'] === 'inactive'){ 
            header('Location: login.php?status=1');  // Send back to login with status = 1
            session_destroy();                       // Destroy session
        } 
    }


}
