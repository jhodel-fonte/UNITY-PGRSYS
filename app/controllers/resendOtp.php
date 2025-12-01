<?php 

require_once __DIR__ .'../../functions/otpFunctions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['otp']) && $_GET['otp'] === '1') {
    //send otp
    if (!isset($_SESSION['number'])) {
        header("Location: ../public/login.php");
        exit;
    }  



}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['resend'] === '1') {
    //resend otp
    if (!isset($_SESSION['number'])) {
        header("Location: ../public/login.php");
        exit;
    }  

    if (sendOtpToNumber($_SESSION['number'])) {
        header("Location: ../../public/auth/otp.php?res=1");
        exit;
    } else {
        header("Location: ../../public/auth/otp.php?res=2");
        exit;
    }


}


?>