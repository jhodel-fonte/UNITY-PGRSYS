<?php

//this one create the function for easy call
require_once __DIR__ . '/../api/sms/messageHandler.php';
require_once __DIR__ . '/../utils/addAllUtil.php';

function sendOtpToNumber($number) {
    $handler = new smsHandler();//call the class
    
    $res = $handler->sendOtp($number);
    $response = json_decode($res, true);

    containlog('Trace:', $response['data']['message'], __DIR__, 'otpLog.log');//otp logging 

    if ($response['status'] == 'success') {//eval the data 
        $_SESSION['otp_sent_time'] = time() + 10;//this is time to limit a resending or refreshing
        $_SESSION['secretOtp'] = $response['data']['message'];
        $_SESSION['num'] = $number;
        $_SESSION['number'] = $number;

        return true;
    }
    return false;
}

function verifyOtpForNumber($number, $otp) {
    $handler = new smsHandler();
    $res = $handler->verifyOtp($number, $otp);
    $response = json_decode($res, true);
    // echo $res;

    if (!isset($response)) {
        $response = ['success' => 'error', 'message' => 'SMS Server Error'];
    }
    
    containlog('Trace', $res, __DIR__, 'otpLog.txt');//otp logging 
    
    if ($response['status'] == 'success' && $response['message'] == 'OTP verified successfully' ) {
            return true;
        }
    return false;
}

function verifyOtpForNumber2($number, $otp) {
    $handler = new smsHandler();
    $rawResponse = $handler->verifyOtp($number, $otp);

    $response = json_decode($rawResponse, true);
    containlog('Trace', "iProgSMS Verify Response: " . $rawResponse, __DIR__, 'otpLog.txt');
    
    if (!is_array($response) || !isset($response['status'])) {
        $response = [
            'status' => 'error',
            'message' => 'SMS Server Error: Invalid or Malformed Response from iProgSMS.'
        ];
        
        containlog('Error', $response['message'], __DIR__, 'otpFailureLog.txt');
        return false;
    }
    
    if ($response['status'] === 'success') {
        return true;
    }
    
    if ($response['status'] !== 'success') {
        $errorMessage = $response['message'] ?? 'Unknown OTP verification error.';
        containlog('Warning', "OTP Verification Failed for {$number}. Status: {$response['status']}. Message: {$errorMessage}", __DIR__, 'otpFailureLog.txt');
    }

    return false;
}

function resendOtp($message, $phoneNumber) {
    $handler = new smsHandler();
    $data = [
        'message' => sanitizeInput($message),
        'number' => sanitizeInput($phoneNumber)
    ];

    $res = $handler->sendSms($data);
    $response = json_decode($res, true);

    //error retun value
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($response) || !isset($response['status'])) {
        return [
            'status' => 'error',
            'code' => 'API_FORMAT_ERROR',
            'message' => 'API returned invalid or unreadable response format.',
            'raw_response' => $res
        ];
    }

    if ($response['status'] === 200) {//success
        $_SESSION['otp_exp'] = time() + 300; 

        return [
            'status' => 'success',
            'message' => 'OTP sent successfully and is valid for 5 minutes.',
            'message_id' => $response['message_id'],
            'expires_at' => $_SESSION['otp_exp']
        ];
    }

    //faliure
    return [
        'status' => 'error',
        'code' => $response['status'],
        'message' => $response['message'] ?? 'Unknown API error occurred.',
        'details' => $response
    ];
}