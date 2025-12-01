<?php
// require_once __DIR__ .'/account.php';
// require_once __DIR__ .'/profile.php';
require_once __DIR__ .'/Db.php';

function getAccountOnlyData($accountId) : array {
    $database = new Database();
    $conn = $database->getConn();

    $query = $conn->prepare("SELECT a.accId, a.username, a.mobileNum, a.roleId, a.statusId, a.pgCode, a.email FROM account as a WHERE a.pgCode = ?");
    $id = sanitizeInput($accountId);
    $query->execute([$id]);
    $result = $query->fetch();

    if ($result) {
        return ['success' => true, 'data' => $result];
    } else {
        $r = 'No Account Found!!';
        containlog('Error', $r, __DIR__, 'database.log');
        return ['success' => false, 'message' => $r];
    }

}

function changePGIDStatus($PGID, $status) {
    $database = new Database();
    $conn = $database->getConn();
    
    $sql = "UPDATE `account` SET `statusId` = ? WHERE `pgCode` = ?";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$status, $PGID]);
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        containlog('ERROR', "Database Error updating status PGID and Status!" , null, 'database.log');  
        return false;
    }
        
}

function changePGIDRole($PGID, $roleNumber) {
    $database = new Database();
    $conn = $database->getConn();
    
    $sql = "UPDATE `account` SET `roleId` = ? WHERE `pgCode` = ?";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$roleNumber, $PGID]);
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        containlog('ERROR', "Database Error updating status PGID and Role!" , null, 'database.log');  
        return false;
    }
        
}

function updateProfileImage($pgCode, $imageDir) {
    $database = new Database();
    $conn = $database->getConn();
    
    $sql = "UPDATE `profile` SET `profileImage` = ?, `isProfileComplete` = 1 WHERE `userId` = ?";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$imageDir, $pgCode]); 
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        $errorInfo = $stmt->errorInfo();
        containlog('ERROR', "Database Error updating status PGID: {$pgCode} with Image: {$imageDir}. Error: {$errorInfo[2]}", null, 'database.log'); 
        return false;
    }
}

function addImageToPgID($pgCode, $imageDir, $type) {
    $database = new Database();
    $conn = $database->getConn();
    
    $sql = "INSERT INTO `images`(`user_id`, `type`, `location`) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$pgCode, $type, $imageDir]); 
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        $errorInfo = $stmt->errorInfo();
        containlog('ERROR', "Database Error inserting status PGID: {$pgCode} with Image: {$imageDir}. Error: {$errorInfo[2]}", null, 'database.log'); 
        return false;
    }
    
}



?>