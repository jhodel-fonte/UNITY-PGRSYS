<?php

session_start();
// Start output buffering (good practice)
ob_start();

// Ensure paths are correct, using dirname() to be safer
require_once dirname(__DIR__) . '/utils/addAllUtil.php';
require_once dirname(__DIR__) . '/functions/allFunctions.php';

// Set content type for JSON responses
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception('Username and Password fields cannot be empty.');
        }

        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);

        $result = loginVerify($username, $password);

        if ($result['response'] == 'success') {
            $userProfile = $result['userprofile'];

            $_SESSION['userLoginData'] = ['success' => true, 'data' => $userProfile];

            // The redirecting.php script will handle OTP checks based on statusId
            // We just need to confirm the login was successful here.
            if (isset($userProfile['statusId']) && $userProfile['statusId'] == 1) {
                 $_SESSION['isOtpVerified'] = true;
            }

            $r = ['response' => 'success', 'data' => $userProfile];
            ob_clean();
            echo json_encode($r);
            exit;

        } else {
            $message = $result['message'] ?? 'Invalid Username or Password'; 
            throw new Exception($message);
        }

    } catch (Exception $ex) {
        ob_clean();
        $r = ['response' => 'error', 'message' => $ex->getMessage()];
        echo json_encode($r);
        exit();
    }
}

// Default response for non-POST requests
ob_clean();
$r = ['response' => 'error', 'message' => 'Request method not allowed.'];
echo json_encode($r);
exit();

?>
