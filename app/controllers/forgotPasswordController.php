<?php
session_start();
require_once __DIR__ .'/../functions/otpFunctions.php';
require_once __DIR__ .'/../database/account.php';
$userAcc = new UserAcc();
ob_start();

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (isset($_POST['mobileNum']) && ($_POST['action'] == 'request_otp' || $_POST['action'] == 'resend_otp') ) {

        $number = $_POST['mobileNum'];

        if (!$userAcc->isUserMobileRegisterd($number)) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'No User Associated to Number';
            echo json_encode($response);
            exit;
        }

        if (!sendOtpToNumber($number)) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'Error OTP Request!';
            echo json_encode($response);
            exit;
        }

        ob_clean();
        $response['status'] = 'success';
        $response['message'] = 'OTP Sent';
        echo json_encode($response);
        exit;

    }
    
    // otp verify then change password
    if (isset($_POST['mobile_number_hidden']) && $_POST['action'] === 'reset_password') {
        $required_fields = ['mobile_number_hidden', 'otp', 'new_password', 'confirm_password'];

        $missing_field = false;
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $missing_field = true;
                break;
            }
        }

        if ($missing_field) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'Missing Fields. Please ensure all required fields are provided.';
            echo json_encode($response);
            exit;
        }

        $number = sanitizeInput($_POST['mobile_number_hidden']);
        $otp = sanitizeInput($_POST['otp']); 
        $newPassword = $_POST['new_password']; 
        $confirmNewPassword = $_POST['confirm_password'];
        
        if (strlen($newPassword) < 8) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'New password must be at least 8 characters long.';
            echo json_encode($response);
            exit;
        }

        if ($newPassword !== $confirmNewPassword) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'New password and confirmation password do not match.';
            echo json_encode($response);
            exit;
        }

        if (verifyOtpForNumber2($number, $otp) == false) {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'Invalid OTP. Please check the code and try again.';
            echo json_encode($response);
            exit;
        }

        if ($userAcc->updatePassByNumber($number, $newPassword)) {
            ob_clean();
            $response['status'] = 'success';
            $response['message'] = 'Your password has been successfully reset. You can now log in.';
            echo json_encode($response);
            exit;
        } else {
            ob_clean();
            $response['status'] = 'error';
            $response['message'] = 'An error occurred while updating your password. Please try again.';
            echo json_encode($response);
            exit;
        }
    }

}


ob_clean();
$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];
echo json_encode($response);
exit;
