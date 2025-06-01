<?php
/**
 * User Style API Endpoint
 * Returns the current user's custom style/theme settings as JSON
 * Used by frontend JavaScript to apply user-selected color themes
 */

session_start();
header('Content-Type:application/json');
echo json_encode($_SESSION["style"]); // Return user's style preferences

