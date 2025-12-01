<?php

$lifetime = 1296000; // 2 hours * 60 minutes * 60 seconds

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/', 
    'domain' => '',
    'secure' => true,
    'httponly' => true
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the array where we'll collect the values
$collectedValues = [];

// Loop through the entire $_SESSION superglobal array
foreach ($_SESSION as $key => $value) {

    echo "Key: " . htmlspecialchars($key) . ", Value: " . htmlspecialchars(print_r($value, true)) . "<br><br>";
}

?>

<a href="public/auth/login.php">Login</a>
<a href="public">Public</a>
<a href="app">APP</a>