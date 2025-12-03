<?php
    require_once __DIR__ .'/../database/comms.php';
    require_once __DIR__ .'/../utils/common.php';
    // http_response_code(200);
    // echo json_encode(['success' => true, 'message' => 'Method not allowedssssss']);
    // exit;

    if (isset($_POST['userId']) && isset($_POST['action'])) {
        $PGID = sanitizeInput($_POST['userId']);
        //approve
        if ($_POST['action'] == 'approve') {
            
            if (!changePGIDStatus($PGID, 1)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cannot Change User Status!!']);
                exit;
            }

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'User Approved!']);
            exit;
        }
            ///////////////////////////rejected
        if ($_POST['action'] == 'reject') {
            
            if (!changePGIDStatus($PGID, 6)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cannot Change User Status!!']);
                exit;
            }

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'User Rejected!']);
            exit;
        }
        
        //delete/////////////////////////
        if ($_POST['action'] == 'delete') {
            require_once __DIR__ .'/../database/profiling.php';
            
            if (!DeleteProfileData($PGID)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Cannot Delete User!']);
                exit;
            }

            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'User Successfully Deleted!']);
            exit;
        }

    } 

    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Missing Data Request!']);
    exit;

?>