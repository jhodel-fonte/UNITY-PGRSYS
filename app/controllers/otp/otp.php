<?php
session_start();

require_once __DIR__ .'../../../functions/otpFunctions.php';
require_once __DIR__ .'../../../utils/common.php';
require_once __DIR__ .'../../../database/accounting.php';
require_once __DIR__ .'../../../database/account.php';

header('Content-Type: application/json');
ob_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['otp']) && $_GET['otp'] == 'request') {
    if (isset($_GET['number'])) {
        
        //check for duplicate number
        $acc = new UserAcc();
        $numEntry = $acc->isUserMobileRegisterd($_GET['number']);
        if ($numEntry != false) {
            ob_clean();
            $response['message'] = 'Number Already Registered';
            echo json_encode($response);
            exit;
        }


        if (!sendOtpToNumber($_GET['number'])) {
            ob_clean();
            $response['message'] = 'Error OTP Request!';
            echo json_encode($response);
            exit;
        }

        ob_clean();
        http_response_code(200);
        $response['message'] = 'Success';
        $response['secretOtp'] = $_SESSION['secretOtp'];
        echo json_encode($response);
        exit;
    }

}

if (isset($_GET['otp']) && $_GET['otp'] == 'verify') {
    if (isset($_POST['mobileNum']) && isset($_POST['otp'])) {
        $otp = sanitizeInput($_POST['otp']);
        $number = sanitizeInput($_POST['mobileNum']);

        if (!verifyOtpForNumber($number, $otp)){
            ob_clean();
            http_response_code(400);
            $response['message'] = 'Invalid Otp';
            echo json_encode($response);
            exit;
        }

        // if (!isset($_SESSION['pgcode'])){
        //     ob_clean();
        //     http_response_code(400);
        //     $response['message'] = 'Error Changing Number Cant Find PGID';
        //     echo json_encode($response);
        //     exit;
        // }

        $PGID = $_SESSION['userLoginData']['data']['pgCode'];

        if (!updateNumberbyPGID($number, $PGID)) {
            ob_clean();
            http_response_code(400);
            $response['message'] = 'Error Updating Number';
            echo json_encode($response);
            exit;
        }

        ob_clean();
        http_response_code(200);
        $response['message'] = 'Success Otp Verification';
        echo json_encode($response);
        exit;

    }
}

if (isset($_GET['otp']) && $_GET['otp'] == 'loginregverify') {
    if (isset($_POST['mobileNum']) && isset($_POST['otp'])){
        
        $otp = sanitizeInput($_POST['otp']);
        $number = sanitizeInput($_POST['mobileNum']);

        if (!verifyOtpForNumber($number, $otp)){
            ob_clean();
            http_response_code(400);
            $response['message'] = 'Invalid Otp';
            echo json_encode($response);
            exit;
        }

        ob_clean();
        http_response_code(200);
        $response['message'] = 'Success Otp Verification';
        echo json_encode($response);
        exit;

    }
    
ob_clean();
http_response_code(400);
$response['message'] = 'Invalid OTP!';
echo json_encode($response);
exit;
}




ob_clean();
http_response_code(400);
$response['message'] = 'Error Request!';
echo json_encode($response);
exit;




?>