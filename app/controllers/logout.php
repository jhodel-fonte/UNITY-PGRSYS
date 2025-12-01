<?php

if (isset($_GET['logout'])){
    session_start();
    session_unset();
    session_destroy();
    echo "<script>alert('Logging Out');</script>";
    header("Location: ../../public/auth/login.php");
    exit;
}


?>

