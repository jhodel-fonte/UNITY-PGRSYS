<?php

function sanitizeInput($input) {//remove whitespace
    // $var = gettype($input);
    $noSlassh = stripslashes($input);    
    return htmlspecialchars(trim($noSlassh));
}

function redirectBasedOnRole($role) {

}


?>