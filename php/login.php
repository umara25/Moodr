<?php 
session_start()
?>
<!doctype html>
<!--
This is the main page.
-->
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Login Page</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div id="container">



        <div id="content"> 
            
            <div id = "login"> 
                <h1>Enter Login</h1>
                <form id = "loginform" action = "loginhandler.php" method = "POST">
                    <input id="username" type = "text" name = "user" placeholder = "Username" required> 
                    <input id="password" type = "password" name = "password" placeholder = "Password" required>
                    <input type = "submit">

                <?php   
            
                    $session = filter_input(INPUT_GET,"create");
                    $failed = filter_input(INPUT_GET,"login");
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