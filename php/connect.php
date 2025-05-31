<?php
/**
 * Database Connection File
 * Establishes PDO connection to MySQL database
 * Used by all other PHP files that need database access
 */

try {
    // Create PDO connection to MySQL database
    $dbh = new PDO(
        "mysql:host=localhost;dbname=FinalProject", // Database host and name
        "root",    // Username
        ""         // Password (empty for local development)
    );
} catch (Exception $e) {
    // Display error message if connection fails
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}