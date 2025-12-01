<?php
require_once __DIR__ .'../../utils/common.php';
require_once __DIR__ .'../../functions/passwordF.php';
require_once __DIR__ .'../../database/databaseFunctions.php';

function loginVerify($uname, $password) {
    try {
        $tempAccount = new UserAcc();

        $result = $tempAccount->getAccByUsername($uname);

        if (isset($result['success']) && $result['success'] === true) {
            
            $accountData = $result['data'];
            $isPassMatched = verifyPassword($password, $accountData['saltedPass']);

            if ($isPassMatched) {

                // Login SUCCESS
                $profile = new profileMng();
                $userProfile = $profile->getProfileDetailsByID($accountData['accId']);

                $response = [
                    'response' => 'success',
                    'userprofile' => $userProfile 
                ];

                $logMesasge = 'Successfully login, USER: ' . $userProfile['pgCode'];
                containlog('INFO', $logMesasge, null, 'userActivity.log');
                
                return $response;

            }
        } 
        
        throw new Exception("Invalid Username or Password");

    } catch (Exception $r) {
        $response = [
            'response' => 'error',
            // Return the generic message to the client
            'message' => "Invalid Username or Password" 
        ];
        containlog('ERROR', 'Login failed: ' . $r->getMessage() . ' for user ' . $uname, null, 'userActivityError.log');
        return $response;
    }
}


?>