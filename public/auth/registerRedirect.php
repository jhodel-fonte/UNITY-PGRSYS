<?php
session_start();

require_once __DIR__ .'../../../app/database/accounting.php';
require_once __DIR__ .'../../../app/utils/log.php';
require_once __DIR__ .'../../../app/functions/otpFunctions.php';
require_once __DIR__ .'../../../app/database/profiling.php';
require_once __DIR__ .'../../../app/database/accounting.php';

// require_once __DIR__ .'/../app/database/comms.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['reg'] == 'success') {
    if ($_GET['otp'] == 'false' && isset($_GET['id'])) {
        echo '1';
        $pgid = sanitizeInput($_GET['id']);

        $data = getProfileAccountByPGID($pgid);
        if ($data['success'] == false) {
            header("Location: ../../error.php");
        }

        $_SESSION['globalNumber'] = $data['data']['mobileNum'];
        $_SESSION['globalPGSYSID'] = $data['data']['pgCode'];
        $_SESSION['globalPGSYSID'] = $data['data'];
 
        if (!sendOtpToNumber($_SESSION['globalPGSYSID'])) {
            header("Location: ../../error.php");
        }

        header("Location: ../auth/otp.php");
        
    }
}





?>