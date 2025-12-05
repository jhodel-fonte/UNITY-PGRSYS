<?php

session_start();
ob_start();

require_once dirname(__DIR__) . '/utils/addAllUtil.php';
require_once dirname(__DIR__) . '/functions/allFunctions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            throw new Exception('Username and Password fields cannot be empty.');
        }

        $usernameRegex = '/^[a-zA-Z0-9_]{3,20}$/';
        if (!preg_match($usernameRegex, $_POST['username'])) {
            throw new InvalidArgumentException("Username must be 3 to 20 characters long and contain only letters, numbers, or underscores.");
        }

        if (strlen($_POST['password']) < 8) {
            // throw new InvalidArgumentException("Password must be at least 8 characters long.");
        } 

        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);

        $result = loginVerify($username, $password);

        if ($result['response'] == 'success') {
            $userProfile = $result['userprofile'];

            $_SESSION['userLoginData'] = ['success' => true, 'data' => $userProfile];

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

// Default response
ob_clean();
$r = ['response' => 'error', 'message' => 'Request method not allowed.'];
echo json_encode($r);
exit();

?>
