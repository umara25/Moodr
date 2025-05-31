<?php 
/**
 * New Account Registration Page
 * Handles user registration form display and processing
 * Validates input data and creates new user accounts
 */
session_start();
?>
<!doctype html>
<!--
Allows users to make a new account
-->
<html>
<script src="../js/newAccount.js"></script>

<?php
// Sanitize and validate form inputs for security
$username = filter_input(INPUT_POST,"user", FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST,"email", FILTER_SANITIZE_SPECIAL_CHARS);
$confirm = filter_input(INPUT_POST,"confirm", FILTER_SANITIZE_SPECIAL_CHARS);
$password = filter_input(INPUT_POST,"password");
$passwordHash = password_hash($password, PASSWORD_BCRYPT); // Hash password for security

// Initialize validation flags
$userExists = false;
$emailExists = false;
$paramsok = false;
$load = true; // Flag to determine if form should be displayed

include "connect.php";

// Check if all required form fields were submitted
if ($username !== null && $password !== null && $email !== null && $confirm !== null
    && $email !== false && $confirm !== false){
    $load = false;
    $paramsok = true;
}

// Process account creation if form was submitted
if ($paramsok) {
    // Check if username or email already exists in database
    $cmd = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
    $stmt = $dbh->prepare($cmd);
    $stmt->execute([$username,$email]);
    $row = $stmt->fetch();
    
    if($row){
        $userExists = true; // User already exists
    }else{
        // Create new user account
        $cmd = "INSERT INTO `users`(`username`, `email`, `role`, `password`) VALUES (?,?,?,?)";
        $stmt = $dbh->prepare($cmd);
        $stmt->execute([$username,$email,"user",$passwordHash]);
        $_SESSION["newUser"]= true; // Set flag for successful registration
        header('Location:login.php'); // Redirect to login page
    }
    if($row["email"] === $email){
        $emailExists = true; // Email already in use
    }
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Create New Account Page</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/newAccount.css">
</head>

<body>
    <div id="header">
         <p id="moodr">M o o d r</p>
    </div>
    
    <div id="container">

        <div id="content"> 

            <div id = "login"> 
                <h1>Create New Account</h1>
                <form id = "newAccountForm" action = "newAccount.php" method = "POST">
                    <input type = "text" name = "user" placeholder = "Username" required id="username" maxlength = "20"> 
                    <input type = "email" name = "email" placeholder = "Email" required id="email">
                    <input type="email" name="confirm" placeholder="Confirm Email" required id="confirm">
                    <input type = "password" name = "password" placeholder = "Password" required id="password">
                    <input type = "submit">
                    <div id="showpas"><input type="checkbox" id="showPassword">Show Password </div>
                    <?php
                    if($emailExists){
                        echo "<p class='warning'>EMAIL ALREADY USED</p>";
                    }elseif($userExists){
                        echo "<p class='warning'>USER ALREADY EXISTS</p>";   
                    }elseif(!$load && !$paramsok){
                        echo "<p class='warning'>ERROR INVALID INPUT</p>";
                    }
                    ?>
                    <a href="login.php">Back to Login</a>
                </form>
                
            </div>

        </div>
        <!-- <div id="footer"> TODO </div> -->

        
    </div>
</body>

</html>