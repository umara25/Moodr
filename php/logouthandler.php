<?php

/** 
 * Logout Handler
 * Handles user logout by destroying the current session
 * Clears all session variables and redirects to login page
 */

session_start();
session_destroy();  // Clear all session data
header('Location: login.php'); // Redirect to login page