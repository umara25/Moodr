<?php 

$name = filter_input(INPUT_GET, "name" ,FILTER_SANITIZE_SPECIAL_CHARS);

$primary = filter_input(INPUT_GET, "primary");

$secondary = filter_input(INPUT_GET, "secondary");

$textbox = filter_input(INPUT_GET,"textbox");

$text = filter_input(INPUT_GET, "text");


/** 
 * Takes an input, and returns true if it is a hexadecimal
 * string 6 characters in length
 */
function validateHex($inp) {
    return preg_match('/^[0-9A-Fa-f]{6}$/', $inp);
}
$paramsok = $primary !== null && $primary !== false && $secondary !== null &&$secondary !== false 
            && $text!== null && $text!==false && validateHex($primary) && validateHex($secondary) 
            && validateHex($text) && $name !== null && $name !== "";


if($paramsok){ 
    //CHECK IF STYLE EXISTS

    include "connect.php";

    $cmd = "SELECT * FROM styles WHERE primary_colour = ? AND secondary_colour = ? AND textbox_colour = ?
            AND text_colour = ?";
    $stmt = $dbh->prepare($cmd);
    $succ = $stmt->execute([$primary,$secondary,$textbox,$text]);

    if($row = $stmt->fetch()){
        //Style already exists 
        echo (-1);
        exit;

    }

    $cmd = "SELECT * FROM styles WHERE `styleID` = ?";
    $stmt = $dbh->prepare($cmd);
    $succ = $stmt->execute([$name]);

    if($row = $stmt->fetch()){
        //Style name already exists 
        echo (-1);
        exit;
    }


    $cmd = "INSERT INTO styles (`styleID`,primary_colour,secondary_colour,textbox_colour,text_colour) VALUES (?,?,?,?,?)";
    $stmt = $dbh->prepare($cmd);
    $suc = $stmt->execute([$name,$primary,$secondary,$textbox,$text]);

    if($suc){ 
        echo (1);
    }

    
}else{
    echo(-1);
}


