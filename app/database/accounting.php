<?php

require_once __DIR__ .'/Db.php';
require_once __DIR__ .'../../utils/common.php';
require_once __DIR__ .'../../functions/passwordF.php';
// require_once __DIR__ .'../../database/account.php';\

function getAccByPGID($id) {
    $database = new Database();
    $conn = $database->getConn();
    
    try {
        $query = $conn->prepare("SELECT * FROM account WHERE account.pgCode = ?");
        $id = sanitizeInput($id);
        $query->execute([$id]);
        $result = $query->fetch();

        if ($result) {

            return ['success' => true, 'message' => 'GetAcc', 'data' => $result];
        } else {
            throw new Exception("No Account results found for ID: $id");
        }

    } catch (Exception $r) {
        $response = ['success' => false, 'message' => $r->getMessage()];
        containlog('Error', $r->getMessage(), __DIR__, 'database.log');
        return $response;
    }
}



function updateAccountByPGID($arrayData) {

    $requiredKeys = ['username', 'password', 'number', 'email', 'pgCode'];
    // var_dump($arrayData);

    foreach ($requiredKeys as $key) {
        if (!isset($arrayData[$key])) {
            containlog('ERROR', "Missing data key '{$key}' in newDataArray for UpdateAllProfileByPGID.", null, 'database.log');
            return false;
        }
    }

    $database = new Database();
    $conn = $database->getConn();

    if (!$conn) {
        containlog('CRITICAL', "Database connection failed in UpdateAllProfileByPGID.", null, 'database.log');
        return false;
    }

    $sql = "UPDATE `account` SET `username`= ?, `saltedPass`= ?,`mobileNum`= ?, `email`= ? WHERE `pgCode` = ?";
    $stmt = $conn->prepare($sql);
    
    $saltesPass = securePassword($arrayData['password']);
    
    try {
        $success = $stmt->execute([
            $arrayData['username'],
            $saltesPass,
            $arrayData['number'],
            $arrayData['email'],
            $arrayData['pgCode'],
        ]);
        
        if ($success) {
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                // No rows affected, might mean data was identical or userId not found
                containlog('INFO', "No rows affected by UpdateAllProfileByPGID for userId: {$arrayData['userId']}. Data might be identical.", null, 'database.log');
                return true;
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            containlog('ERROR', "Database Execute Error in UpdateAllProfileByPGID for userId: {$arrayData['userId']}. Error: {$errorInfo[2]}", null, 'database.log');
            return false;
        }
    } catch (PDOException $e) {
        containlog('ERROR', "PDO Exception in UpdateAllProfileByPGID for userId: {$arrayData['userId']}. Message: {$e->getMessage()}", null, 'database.log');
        return false;
    }

}

function updateSomeAccountByPGID($arrayData) {

    $requiredKeys = ['username', 'number', 'email', 'pgCode'];
    // var_dump($arrayData);

    foreach ($requiredKeys as $key) {
        if (!isset($arrayData[$key])) {
            containlog('ERROR', "Missing data key '{$key}' ain newDataArray for UpdateAllProfileByPGID.", null, 'database.log');
            return false;
        }
    }

    $database = new Database();
    $conn = $database->getConn();

    if (!$conn) {
        containlog('CRITICAL', "Database connection failed in UpdateAllProfileByPGID.", null, 'database.log');
        return false;
    }

    $sql = "UPDATE `account` SET `username`= ?, `mobileNum`= ?, `email`= ? WHERE `pgCode` = ?";
    $stmt = $conn->prepare($sql);
        
    try {
        $success = $stmt->execute([
            $arrayData['username'],
            $arrayData['number'],
            $arrayData['email'],
            $arrayData['pgCode'],
        ]);
        
        if ($success) {
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                // No rows affected, might mean data was identical or userId not found
                containlog('INFO', "No rows affected by UpdateAllProfileByPGID for userId: {$arrayData['pgCode']}. Data might be identical.", null, 'database.log');
                return true;
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            containlog('ERROR', "Database Execute Error in UpdateAllProfileByPGID for userId: {$arrayData['userId']}. Error: {$errorInfo[2]}", null, 'database.log');
            return false;
        }
    } catch (PDOException $e) {
        containlog('ERROR', "PDO Exception in UpdateAllProfileByPGID for userId: {$arrayData['userId']}. Message: {$e->getMessage()}", null, 'database.log');
        return false;
    }

}

function updatePassword($newPass, $PGID) {
    $database = new Database();
    $conn = $database->getConn();

    $sql = "UPDATE `account` SET `saltedPass` = ? WHERE `pgCode` = ?";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$newPass, $PGID]); 
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            containlog('ERROR', "Database Error executing update for PGID: {$PGID}. Error: {$errorInfo[2]}", null, 'database.log'); 
        } 
        return false;
    }
}

function updateNumberbyPGID($newNum, $PGID) {
    $database = new Database();
    $conn = $database->getConn();

    $sql = "UPDATE `account` SET `mobileNum` = ? WHERE `pgCode` = ?";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute([$newNum, $PGID]); 
    
    if ($success && $stmt->rowCount() > 0) {
        return true;
    } else {
        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            containlog('ERROR', "Database Error executing update for PGID: {$PGID}. Error: {$errorInfo[2]}", null, 'database.log'); 
        } 
        return false;
    }

}





?>