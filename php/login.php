
<?php 

    /** 
     * Password verification 
     * Checks if user exists in database, and if they do 
     * checks if the password matches
     */
    session_start();
    include "connect.php";


    $username = filter_input(INPUT_POST,"user"); 
    $password = filter_input(INPUT_POST,"password");


    if($username !== null && $password !== null){ 

        /** 
         * Prepare SELECT command to see if they exist
         */
        $cmd = "SELECT password,role FROM users WHERE username = ?";
        $stmt = $dbh->prepare($cmd);
        $success = $stmt->execute([$username]);

        //Check if user found
        if($row = $stmt->fetch()){ 

            if(password_verify($password,$row["password"])){ 
                //Access granted
                $_SESSION["username"] = $row["username"];
                $_SESSION["role"] = $row["role"];
                echo"<h1>SUCCESS</h1>";


            }else {
                echo"<h1>FAIL</h1>";
                // bad login attempt. kick them out.
                session_unset();
                session_destroy();

            }
        }else {
            echo"<h1>FAIL</h1>";
            // bad login attempt. kick them out.
            session_unset();
            session_destroy();
        }

        // echo password_hash("kiwipassword", PASSWORD_BCRYPT); 
    }

