<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start(); 

require_once __DIR__ . '../../utils/addAllUtil.php';
require_once __DIR__ . '../../functions/allFunctions.php';
require_once __DIR__ . '../../database/profiling.php';

header('Content-Type: application/json');

$requiredFields = [
    'firstname', 'lastname', 'mobile', 'email', 'barangay', 'city', 
    'province', 'country', 'username', 'password', 'confirm_password', 
    'dob', 'role', 'status', 'gender'
];

$missingFields = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_GET['register']) && $_GET['register'] == 1) {
        try {
            
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }
            
            if (!empty($missingFields)) {
                 throw new InvalidArgumentException("Missing required fields: " . implode(', ', $missingFields));
            }

            $usernameRegex = '/^[a-zA-Z0-9_]{3,20}$/';
            if (!preg_match($usernameRegex, $_POST['username'])) {
                throw new InvalidArgumentException("Username must be 3 to 20 characters long and contain only letters, numbers, or underscores.");
            }

            // input Validation

            //email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException("Invalid email address format.");
            }
            
            // Password length check (re-enabled and recommended minimum)
            if (strlen($_POST['password']) < 8) {
                throw new InvalidArgumentException("Password must be at least 8 characters long.");
            } 


            //

            // Password match check
            if ($_POST['password'] !== $_POST['confirm_password']) {
                throw new InvalidArgumentException("Passwords do not match.");
            }

            $userReg = [
                // Clean data before use
                "firstName" => sanitizeInput($_POST['firstname']),
                "lastName" => sanitizeInput($_POST['lastname']),
                "number" => sanitizeInput($_POST['mobile']),
                "email" => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
                "gender" => sanitizeInput($_POST['gender']),
                "address" => sanitizeInput($_POST['barangay']) . ", " . 
                             sanitizeInput($_POST['city']) . ", " . 
                             sanitizeInput($_POST['province']) . ", " . 
                             sanitizeInput($_POST['country']),
                "username" => sanitizeInput($_POST['username']),
                "pass" => $_POST['password'], 
                "dob" => sanitizeInput($_POST['dob']),
                "role" => sanitizeInput($_POST['role']),
                "status" => sanitizeInput($_POST['status'])
            ];


            // --- 6. Account Creation and Error Check ---
            $set = CreateNewUserAccount($userReg);
            
            if ($set['response'] === 'error'){
                throw new RuntimeException($set['message']); 
            }
            
            // Set session data after successful creation
            
            $_SESSION['number'] = $userReg['number'];
            $_SESSION['userLoginData'] = getProfileAccountByPGID($set['pgID']);
            
            //send otp 
            if (!sendOtpToNumber($userReg['number'])) {                
                containlog('WARNING', 'OTP send failed for new user PGID: ' . $set['pgID'], null, 'authLog.log');
            }
            
            containlog('INFO', 'User registered successfully. PGID: ' . $set['pgID'] . ', Username: ' . $userReg['username'], null, 'authLog.log');
            containlog('ACCOUNT', 'User registered successfully. PGID: ' . $set['pgID'] . ', Username: ' . $userReg['username'] .': Need Admin Approval', null, 'createAccNotif.txt');
            
            
            ob_clean();
            echo json_encode([
                'status' => 'success', 
                'message' => 'Registered Successfully. Please check your phone for OTP.', 
                'PGID' => $set['pgID']
            ]);

            exit;
 
        } catch (InvalidArgumentException $e) {
            containlog('ERROR', 'Registration validation error: ' . $e->getMessage(), null, 'authLog.log');
            ob_end_clean();
            http_response_code(400);
            echo json_encode(['status' => 'error','message' => $e->getMessage()]);
            exit();
            
        } catch (RuntimeException $e) {
            containlog('ERROR', 'Registration business logic error: ' . $e->getMessage(), null, 'authLog.log');
            ob_end_clean();
            http_response_code(409);
            echo json_encode(['status' => 'error','message' => $e->getMessage()]);
            exit();
            
        } catch (\Exception $e) {
            containlog('CRITICAL', 'Unhandled registration error: ' . $e->getMessage(), null, 'authLog.log');
            ob_end_clean();
            http_response_code(500);
            echo json_encode(['status' => 'error','message' => 'An unexpected error occurred. Please try again later.']);
            exit();
        }

    }
    
    ob_end_clean();
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or action specified.']);
    exit();
}

ob_end_clean();
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed for registration.']);

?>