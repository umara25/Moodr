<?php
session_start();
header('Content-Type:application/json');

if(isset($_SESSION["style"])){ 
    echo json_encode($_SESSION["style"]);
}else{  // Nothing is set, meaning they aren't logged in
    echo json_encode(-1);
}
    