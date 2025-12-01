<?php
require_once __DIR__ . '../../database/profile.php';
require_once __DIR__ . '../../database/account.php';

function CreateNewUserAccount($arrayInfo) {
    $requiredKeys = ['firstName', 'lastName', 'number', 'email', 'address', 'username', 'pass', 'role', 'status'];
    foreach ($requiredKeys as $key) {
        if (!isset($arrayInfo[$key])) {
            throw new InvalidArgumentException("Missing required input parameter: $key");
        }
    }
    
    // 2. Class Initialization (Moved outside try for better scope if needed)
    $userProfile = new profileMng();
    $userAcc = new UserAcc();

    // Variable assignment
    $firstName = $arrayInfo['firstName'];
    $lastName = $arrayInfo['lastName'];
    $gender = $arrayInfo['gender'] ?? null; 
    $dob = $arrayInfo['dob'] ?? null;
    $number = $arrayInfo['number'];
    $email = $arrayInfo['email'];
    $address = $arrayInfo['address'];
    $username = $arrayInfo['username'];
    $pass = $arrayInfo['pass']; // NOTE: Password should be hashed before storage!
    $role = $arrayInfo['role'];
    $status = $arrayInfo['status'];
    
    // Initial profile ID set to null for cleanup logic
    $pgCode = null; 

    try {
        // --- Pre-registration Checks ---

        // Check if username is already registered
        if ($userAcc->isUsernameRegistered($username)) {
            throw new Exception('Username is already registered.');
        }

        // Check if mobile number is already registered
        if ($userAcc->isUserMobileRegisterd($number)) {
            throw new Exception('Mobile Number is already registered!');
        }
        
/*         // Check if email is already registered 
        if ($userAcc->isUserEmailRegistered($email)) {
            throw new Exception('Email address is already registered!');
        } */

        // --- Profile Creation ---
        $profileResult = $userProfile->addProfile($firstName, $lastName, $gender, $dob, $role, $address);
        
        if (!isset($profileResult['pgID']) || !is_int($profileResult['pgID']) || $profileResult['pgID'] <= 0) {
             $errorMsg = $profileResult['error'] ?? 'Unknown profile creation error.';
             throw new Exception('Profile creation failed: ' . $errorMsg);
        }

        $pgCode = $profileResult['pgID']; 
        
        // --- Account Creation ---
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT); 
        
        $regResult = $userAcc->addAccount($username, $hashedPass, $number, $pgCode, $email, $role, $status);
        
        if (!isset($regResult['success']) || $regResult['success'] === false) {
            $userProfile->deleteUser($pgCode);
            $errorMsg = $regResult['error'] ?? 'Account registration failed.';
            throw new Exception('Account registration failed: ' . $errorMsg);
        }

        // --- Success Response ---
        return [
            'response' => 'success', 
            'pgID' => $pgCode
        ];
        
    } catch (\InvalidArgumentException $e) {
        containlog('Input ERROR', $e->getMessage(), __DIR__, 'validation.log');
        return ['response' => 'error', 'message' => $e->getMessage()];
        
    } catch (\Exception $e) {
        containlog('Database INFO', $e->getMessage(), __DIR__, 'database.log');
        
        if ($pgCode !== null) {
            $userProfile->deleteUser($pgCode);
        }
        
        return ['response' => 'error', 'message' => $e->getMessage()];
    }
}
