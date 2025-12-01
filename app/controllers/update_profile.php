<?php
session_start();
header('Content-Type: application/json');


require_once __DIR__ . '../../database/profiling.php'; 
require_once __DIR__ . '../../database/account.php'; 
require_once __DIR__ . '../../database/accounting.php'; 
require_once __DIR__ . '../../utils/addAllUtil.php'; // Assuming common.php is here

$response = ['success' => false, 'message' => 'An unexpected error occurred.'];

if (isset($_GET['profile'])) {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // 1. Authentication Check
        if (!isset($_SESSION['userLoginData']) || !isset($_SESSION['userLoginData']['data']['userId'])) {
            $response['message'] = 'Authentication error: User not logged in or user ID missing from session.';
            echo json_encode($response);
            exit;
        }

        $adminId = $_SESSION['userLoginData']['data']['userId'];
        $currentProfileImage = $_SESSION['userLoginData']['data']['profileImage'] ?? 'default_profile.png';
        
        // Ensure all expected POST variables are set before trying to access them
        $firstName = $_POST['firstName'] ?? '';
        $lastName = $_POST['lastName'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $address = $_POST['address'] ?? '';
        $mobileNum = $_POST['mobileNum'] ?? '';
        $username = $_POST['username'] ?? '';
        $email = sanitizeInput($_POST['email']) ?? '';
        $username = sanitizeInput($_POST['username']) ?? '';

        $accArray = [
            'username' => $username,
            "email" => $email,
            "number" => $mobileNum,
            "pgCode" => $adminId
        ];

        // $acc = new UserAcc();
        // if ($acc->isUserMobileRegisterd($mobileNum) != false) {
        //     $response['message'] = 'Error Mobile Number';
        //     echo json_encode($response);
        //     exit;
        // }

        // 2. Input Sanitation and Validation
        $profileData = [
            'firstName'  => sanitizeInput($firstName),
            'lastName' => sanitizeInput($lastName),
            'gender' => sanitizeInput($gender),
            'dateOfBirth' => sanitizeInput($dob), // Use 'dateOfBirth' key to match database/session
            'address' => sanitizeInput($address),
            'profileImage'=> $currentProfileImage, // This will be updated below
            'userId' => $adminId // The ID for the WHERE clause
        ];

        // Check for critical required fields (you may add more based on your app's logic)
        if (empty($profileData['firstName']) || empty($profileData['lastName'])) {
            $response['message'] = 'First Name and Last Name are required.';
            echo json_encode($response);
            exit;
        }


        // 3. Handle Profile Picture Upload (Standard File Input)
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_pic'];

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB

            if (!in_array($file['type'], $allowedTypes)) {
                $response['message'] = 'Invalid file type. Only JPG, PNG, GIF allowed.';
                echo json_encode($response);
                exit;
            }
            
            if ($file['size'] > $maxFileSize) {
                $response['message'] = 'File size exceeds 2MB limit.';
                echo json_encode($response);
                exit;
            }
            
            // Target uploads directory: From /app/controllers/ to /uploads/
            $uploadDir = __DIR__ . '../../../uploads/'; // Up 3 levels to project root, then down to uploads/

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFileName = uniqid('profile_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $targetFilePath = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                // Delete old profile image if it's not the default
                $oldProfileImage = $_SESSION['userLoginData']['data']['profileImage'] ?? '';
                if (!empty($oldProfileImage) && $oldProfileImage !== 'default_profile.png') {
                    $oldFilePath = $uploadDir . $oldProfileImage;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                $profileData['profileImage'] = $newFileName; // Update the image name
            } else {
                $response['message'] = 'Failed to upload new profile picture.';
                containlog('ERROR', "Failed to move uploaded file for admin ID: {$adminId}. Check permissions on {$uploadDir}", null, 'file_upload.log');
                echo json_encode($response);
                exit;
            }
        }

        if (!updateSomeAccountByPGID($accArray)) {
            $response['message'] = 'Failed to update profile. This often means no data changed or there was a database error.';
            echo json_encode($response);
            exit;
        };

        if ($_SESSION['userLoginData'] != $mobileNum) {
            $_SESSION['isOtpVerified'] = false;
        }

        // 4. Database Update
        if (UpdateAllProfileByPGID($profileData)) {

            $_SESSION['userLoginData'] = getProfileAccountByPGID($profileData['userId']);
            $response['success'] = true;
            $response['message'] = 'Profile updated successfully!';
            $response['newProfileImage'] = '../uploads/' . htmlspecialchars($profileData['profileImage']);

        } else {
            $response['message'] = 'Failed to update profile. This often means no data changed or there was a database error.';
        }

    } else {
        $response['message'] = 'Invalid request method.';
    }
    
    echo json_encode($response);
    exit;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//account update

if (isset($_GET['account'])) {
    require_once __DIR__ . '../../database/accounting.php'; 

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Ensure all expected POST variables are set before trying to access them
        $pgID = sanitizeInput($_POST['pgID']) ?? '';        
        $mobileNum = sanitizeInput($_POST['mobileNum']) ?? '';
        $email = sanitizeInput($_POST['email']) ?? '';
        $username = sanitizeInput($_POST['username']) ?? '';

        if ($newPass != $confirmNewPassword) {
            $response['message'] = 'Password Not Match';
            echo json_encode($response);
            exit;
        }
        
        //authcheck
        if (!isset($_SESSION['userLoginData']) || !isset($_SESSION['userLoginData']['data']['userId'])) {
            $response['message'] = 'Authentication error: User not logged in or user ID missing from session.';
            echo json_encode($response);
            exit;
        }

        $thisAccount = getAccByPGID($pgID);

        if (!password_verify($currentPass, $thisAccount['data']['saltedPass'])) {
            $response['message'] = 'Invalid Password';
            echo json_encode($response);
            exit;
        }

        $inputData = [
            "username" => $username,
            "number" => $mobileNum,
            "password" => $newPass,
            "email" => $email,
            "pgCode" => $pgID
        ];

        if (updateAccountByPGID($inputData)) {
            $_SESSION['userLoginData'] = getProfileAccountByPGID($_SESSION['pgcode']);

            $response['success'] = true;
            $response['message'] = 'Profile updated successfully!';


        } else {            
            $response['message'] = 'Error Updating Account';
            echo json_encode($response);
            exit;

        }


    }
echo json_encode($response);
exit;

}
