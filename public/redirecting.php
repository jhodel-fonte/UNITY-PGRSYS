<?php

require_once __DIR__ .'/../app/functions/allFunctions.php';
require_once __DIR__ .'/../app/database/profiling.php';
require_once __DIR__ .'/../app/database/comms.php';



function redirectBasedOnRole1($role) {
    if ($role == 'Admin') {
        header("Location: ../admin/dashboard.php"); // Adjusted path for login page location
        exit;
    }

    if ($role == 'ResponseTeam') {
       header("Location: ../public/response_team/");
        exit;
    }

    if ($role == 'User') {
        header("Location: ../admin/dashboard.php"); // Adjusted path for login page location
        exit;
    }
    
    header("Location: ../../error.php"); // Adjusted path
    exit;

}

session_start();
var_dump($_SESSION['userLoginData']['data']);

if (isset($_SESSION['userLoginData']) ) {
  //check for updates
  $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['userLoginData']['data']['pgCode']);

} else {
    header('Location: ../auth/login.php');
}

if (isset($_GET['reg']) && $_GET['reg'] == 'success') {
    // header('Location: ../public/auth/');
    sendOtpToNumber($data['data']['mobileNum']);
    $_SESSION['userLoginData']['data'] = $data['data'];
    header('Location: ./auth/otp.php');
    exit;
}


if ($_SESSION['userLoginData']['data']['isProfileComplete'] == false && $_SESSION['isOtpVerified'] == true ) {
    header('Location: ../public/auth/selfie.php');
    exit;
}

if (isset($_SESSION['isOtpVerified']) && $_SESSION['isOtpVerified'] === true && isset($_SESSION['userLoginData']['data']['role'])) {
    // var_dump($_SESSION['userLoginData']['data']['role']);
    redirectBasedOnRole1($_SESSION['userLoginData']['data']['role']);
    exit();
}

if (isset($_SESSION['userLoginData']['data']['pgCode'])) {
    $PGID = $_SESSION['userLoginData']['data']['pgCode'];
    $data = getProfileAccountByPGID($PGID);

    //check if number registered otp same when logging in
    if ($data['success'] && ($data['data']['statusId'] == 5 || $data['data']['statusId'] == 'NoOtpReg') || isset($_GET['login']) && $_GET['login'] == '1') {
        
        sendOtpToNumber($data['data']['mobileNum']);
        $_SESSION['userLoginData']['data'] = $data['data'];
        header('Location: ./auth/otp.php');
        exit;
    }

    //check if the user complete the requirements redirect to complete the check
    

}
// Default redirect to login if no other conditions are met
header('Location: ./auth/login.php');
exit;

?>
