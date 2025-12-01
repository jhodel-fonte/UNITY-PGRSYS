<?php
require_once __DIR__ . '/../functions/allFunctions.php';
require_once __DIR__ . '/../utils/addAllUtil.php';
require_once __DIR__ . '/../database/comms.php';
require_once __DIR__ . '/../database/profiling.php';


// unset($_SESSION['']);

// foreach ($_POST as $key => $value) {  /////comment this when debbugging
//     echo "$key: $value<br>";
//     $array[] = $value;
// }

// var_dump($array);

ob_start();
header('Content-Type: application/json;');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_POST['otp'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'OTP not provided']);
        exit;
    }

    $otp = sanitizeInput($_POST['otp']);
    // Check if user session exists
    if (empty($_SESSION['number']) || !isset($_SESSION['userLoginData'])) {
        ob_clean();
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid User Session. Please login again.']);
        exit;
    }

    $number = $_SESSION['number'];

    if (verifyOtpForNumber($number, $otp)) {
        $_SESSION['isValid'] = 1;
        $_SESSION['isOtpVerified'] = true;
        
        $msg = json_encode(['success' => true, 'message' => 'OTP verified Successfully']);
        containlog('Log', $msg, __DIR__, 'verifyOtp.txt');
        ob_clean();
        echo $msg;
        changePGIDStatus($_SESSION['userLoginData']['data']['pgCode'], 1);
        exit;
    }
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid OTPs']);
    exit;

} catch (Exception $e) {
    ob_clean();
    error_log('OTP verification error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
    exit;
}

?>

