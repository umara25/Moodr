<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=FinalProject",
        "root",
        ""
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}