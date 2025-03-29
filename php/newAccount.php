<!doctype html>
<!--
Allows users to make a new account
-->
<html>
<script src="../js/newAccount.js"></script>

<?php
$username = filter_input(INPUT_POST,"username");
$email = filter_input(INPUT_POST,"email", FILTER_SANITIZE_SPECIAL_CHARS);
$confirm = filter_input(INPUT_POST,"confirm", FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST,"password");
$passwordHash = password_hash($password, PASSWORD_BCRYPT);
$userExists = false;
$paramsok = false;

include "connect.php";

if ($username !== null and $password !== null and $email !== null and $confirm !== null){
    $paramsok = true;
}

if ($paramsok) {
    $cmd = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $dbh->prepare($cmd);
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    if($row){
        $userExists = true;
    }else{
        $cmd = "INSERT INTO `users`(`username`, `email`, `role`, `password`) VALUES (?,?,?,?)";
        $stmt = $dbh->prepare($cmd);
        $stmt->execute([$username,$email,"user",$passwordHash]);
        $row = $stmt->fetch();
       
    }

    
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Create New Account Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div id="container">

        <div id="content"> 

            <div id = "login"> 
                <h1>Create New Account</h1>
                <form id = "newAccountForm" action = "newAccount.php" method = "POST">
                    <input type = "text" name = "user" placeholder = "Username" required id="username"> 
                    <input type = "email" name = "email" placeholder = "Email" required id="email">
                    <input type="email" required name="confirm" placeholder="Confirm Email" id="confirm">
                    <input type = "password" name = "password" placeholder = "Password" required id="password">
                    <input type = "submit">
                </form>
                
            </div>

        </div>
        <!-- <div id="footer"> TODO </div> -->

        
    </div>
</body>

</html>