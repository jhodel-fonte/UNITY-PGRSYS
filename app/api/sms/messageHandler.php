<?php

//this class is the main for sms api

class smsHandler {
    private $smsUrl = 'https://sms.iprogtech.com/api/v1/sms_messages';
    private $otpUrl = 'https://www.iprogsms.com/api/v1/otp/send_otp';
    // private $apiToken = '92b811ed5d06c8640a7122f69ac1eac583b41b55'; //prod api
    private $apiToken = '9cc11e308a4be7928384edea53822586b90f7526'; //test api $test_api_token

    public function sendSms($dataArray) {//message phone number
        $data = [
            'api_token' => $this->apiToken,
            'message' => isset($dataArray['message']) ? $dataArray['message'] : '',
            'phone_number' => isset($dataArray['phoneNumber']) ? $dataArray['phoneNumber'] : (isset($dataArray['phoneNunber']) ? $dataArray['phoneNunber'] : '')
        ];

        $ch = curl_init($this->smsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function sendOtp($number) {
        $data = [
            'api_token' => $this->apiToken,
            'phone_number' => $number
        ];

        $ch = curl_init($this->otpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    public function verifyOtp($num, $otp) {
        $url = 'https://sms.iprogtech.com/api/v1/otp/verify_otp';

        $data = [
            'api_token' => $this->apiToken,
            'phone_number' => $num,
            'otp' => $otp
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}