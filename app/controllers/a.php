<?php

session_start();
ob_start();

require_once __DIR__ .'../../utils/addAllUtil.php';
require_once __DIR__ .'../../functions/allFunctions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //add the condition that checks for user 
    try {
        if (!isset($_POST['username']) && !isset($_POST['password'])) {
            throw new Exception('Empty Input');
        }

        $username = sanitizeInput($_POST['username']);
        $password = sanitizeInput($_POST['password']);

        $result = loginVerify($username, $password);//check if the credential correct return data

        if ($result['response'] == 'success') {
            //set the data to session 
            $dataStorage = [
                'success' => true,
                'data' => $result['userprofile']
            ];

            $_SESSION['userLoginData'] = $dataStorage;
            $_SESSION['isOtpLoginVerified'] = false;

            ob_clean();
            $r = ['response' => $result['response'], 'data' => $result['userprofile'] ];
            echo json_encode($r, JSON_PRETTY_PRINT);
            exit;

        } else {
            ob_clean();
            throw new Exception('Invalid Login Credentials');
            exit;
        }

    } catch (Exception $ex) {
        ob_clean();
        $r = ['response' => 'error', 'message' => $ex->getMessage()];
        echo json_encode($r, JSON_PRETTY_PRINT);
        exit();
    }


}

$r = ['response' => 'error', 'message' => 'server request error.'];
ob_clean();
echo json_encode($r, JSON_PRETTY_PRINT);

?>