<?php 
session_start();
include "connect.php";

$cmd = "SELECT `role` FROM users WHERE username = ?"; 
$stmt = $dbh->prepare($cmd); 
$succ = $stmt->execute([$_SESSION["username"]]);

if($row = $stmt->fetch()){ 
    //Slected for a row 

    if($_SESSION["role"] !== $row["role"]){ 
        //Actual row doesn't match up with session role
        session_destroy();
        header('Location: login.php');
        exit;
    }

}