<?php

/** 
 * Logout Handler
 * If the user/admin wants to log out, they are directed to
 * this page which will clear their session variables.
 */
session_start();
session_destroy();  // Destroy the session
header('Location: login.php');