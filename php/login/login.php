<?php 
/**
 * Login Page
 * Provides user authentication interface
 * Handles login form display and error messages
 */
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
    <script src = "../js/login/login.js"></script>
</head>

<body>
    <div id="header">
         <p id="moodr">M o o d r</p>
    </div>
   
    <div id="container">
        <div id="content"> 
            <div id = "login"> 
                <h1>Enter Login</h1>
                <!-- Login form - submits to loginhandler.php -->
                <form id = "loginform" action = "loginhandler.php" method = "POST">
                    <input id="username" type = "text" name = "user" placeholder = "Username" required maxlength = "20"> 
                    <input id="password" type = "password" name = "password" placeholder = "Password" required minlength = "8">
                <input type = "submit">

                <?php   
                    // Display login error message if previous attempt failed
                    if(isset($_SESSION["loginFail"])){ 
                        echo "<p class = 'warning'>INVALID LOGIN</p>";
                        $_SESSION["loginFail"] = null;  // Clear session variable after displaying
                        
                    }elseif(isset($_SESSION["newUser"])){ // Account creation success message
                        echo "<p class = 'create'>ACCOUNT CREATED SUCCESSFULLY</p>";
                        $_SESSION["newUser"] = null; //Clear session variable
                    }
                    elseif(isset($_SESSION["inactive"])){ // Account banned message
                        echo "<p class = 'warning'>THIS ACCOUNT IS BANNED</p>";
                        $_SESSION["inactive"] = null; //Clear session variable
                    }

                    // Used for adminHandler check 
                    $badLogin = filter_input(INPUT_GET,"status",FILTER_VALIDATE_INT);
                    if($badLogin !== null && $badLogin !== false){ 
                        if($badLogin === 1){  // status = 1 means adminHandler failed
                            echo "<p class = 'warning'>SESSION EXPIRED, PLEASE LOGIN AGAIN</p>";
                        }
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