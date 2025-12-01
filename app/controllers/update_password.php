<?php

include_once __DIR__ .'../../functions/passwordF.php';//this one contain the sucure password 
include_once __DIR__ .'../../database/account.php';//this one have a function update acc password 
include_once __DIR__ .'../../database/accounting.php';//this one have a function update acc password 

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if any of the required fields are missing or empty
    if (empty($_POST['pgId']) || empty($_POST['currentPassword']) || empty($_POST['newPassword']) || empty($_POST['confirmNewPassword'])) {
        $response['message'] = 'Missing Fields. Please provide your User ID, current password, new password, and confirmation password.';
        echo json_encode($response);
        exit;
    }

    // Assign variables after successful check, using null-coalescing for extra safety (though already checked by `empty`)
    $id = $_POST['pgId'] ?? '';
    $oldPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmNewPassword = $_POST['confirmNewPassword'] ?? '';


    $thisAccount = getAccByPGID($id);

    if (!password_verify($oldPassword, $thisAccount['data']['saltedPass'])) {
        $response['message'] = 'Invalid Password!';
        echo json_encode($response);
        exit;
    }

    if ($confirmNewPassword != $newPassword) {
        $response['message'] = 'Invalid Password.';
        echo json_encode($response);
        exit;
    }

    $saltedPass = securePassword($newPassword);

    if (updatePassword($saltedPass, $id)){
        $response['success'] = true;
        $response['message'] = 'Password Updated';
        echo json_encode($response);
        exit;
    }

    $response['message'] = 'Invalid Password';
    echo json_encode($response);
    exit;
    
}

$response['message'] = 'Invalid Request';
echo json_encode($response);
exit;

?>