<?php 
session_start();
?>
<!doctype html>
<!--
This is the login page
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
    <script src = "../js/login.js"></script>
</head>

<body>
    <div id="header">
         <p id="moodr">M o o d r</p>
    </div>
   

    <div id="container">



        <div id="content"> 
            
            <div id = "login"> 
                <h1>Enter Login</h1>
                <form id = "loginform" action = "loginhandler.php" method = "POST">
                    <input id="username" type = "text" name = "user" placeholder = "Username" required maxlength = "20"> 
                    <input id="password" type = "password" name = "password" placeholder = "Password" required minlength = "8">
                <input type = "submit">

                <?php   
                    //Came from user creation page
                    // if($failed!== null){
                    if(isset($_SESSION["loginFail"])){
                        echo "<p class = 'warning'>INVALID LOGIN</p>";
                        $_SESSION["loginFail"] = null;  //Clear session variable
                        
                    }elseif(isset($_SESSION["newUser"])){
                        echo "<p class = 'create'>ACCOUNT CREATED SUCCESSFULLY</p>";
                        $_SESSION["newUser"] = null; //Clear session variable
                    }
                    ?>
                    <a href="newAccount.php">Create new account</a>
                </form>
                
            </div>

        </div>

        <!-- <div id="footer"> TODO </div> -->

        
    </div>
</body>

</html>