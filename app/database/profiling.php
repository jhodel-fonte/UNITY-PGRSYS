<?php

require_once __DIR__ .'/Db.php';
require_once __DIR__ .'../../utils/common.php';

function getProfileAccountByPGID($PGiD) {
    $database = new Database();
    $conn = $database->getConn();

    $sql = 
        "SELECT 
            a.accId, 
            a.username, 
            a.email, 
            a.mobileNum, 
            a.pgCode, 
            a.is_approved,
            rl.name AS role, 
            st.Name AS status, 
            p.* 
        FROM account AS a 
        INNER JOIN profile AS p ON a.pgCode = p.userId  
        LEFT JOIN roles AS rl ON rl.roleId = a.roleId
        LEFT JOIN status AS st ON st.statusId = a.statusId
        WHERE a.pgCode = ? 
    LIMIT 1";

    try {
        $query = $conn->prepare($sql);
        
        $query->execute([$PGiD]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return ['success' => true, 'data' => $result];
        } else {
            $message = "No Account Found for PGID: $PGiD";
            containlog('Error', $message, __DIR__, 'database.log');
            return ['success' => false, 'message' => $message];
        }

    } catch (PDOException $e) {
        $message = "Database Error in getProfileAccountByPGID: " .$PGiD . $e->getMessage();
        containlog('Error', $message, __DIR__, 'database.log');
        return ['success' => false, 'message' => $message];
    }
}

function UpdateAllProfileByPGID($newDataArray) {
    $requiredKeys = ['firstName', 'lastName', 'gender', 'dateOfBirth', 'profileImage', 'address', 'userId'];
    foreach ($requiredKeys as $key) {
        if (!isset($newDataArray[$key])) {
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

    $sql = "UPDATE `profile` SET
                `firstName` = ?,
                `lastName` = ?,
                `gender` = ?,
                `dateOfBirth` = ?,
                `profileImage` = ?,
                `address` = ?
    WHERE `userId` = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $errorInfo = $conn->errorInfo();
        containlog('ERROR', "SQL Prepare Error in UpdateAllProfileByPGID: {$errorInfo[2]}", null, 'database.log');
        return false;
    }

    try {
        $success = $stmt->execute([
            $newDataArray['firstName'],
            $newDataArray['lastName'],
            $newDataArray['gender'],
            $newDataArray['dateOfBirth'],
            $newDataArray['profileImage'],
            $newDataArray['address'],
            $newDataArray['userId']
        ]);

        if ($success) {
            // rowCount() check is good for UPDATE to confirm actual changes
            if ($stmt->rowCount() > 0) {
                return true; // Changes were made
            } else {
                // No rows affected, might mean data was identical or userId not found
                containlog('INFO', "No rows affected by UpdateAllProfileByPGID for userId: {$newDataArray['userId']}. Data might be identical.", null, 'database.log');
                return true; // Treat as success if no error, as data is effectively "updated" to current state
            }
        } else {
            $errorInfo = $stmt->errorInfo();
            containlog('ERROR', "Database Execute Error in UpdateAllProfileByPGID for userId: {$newDataArray['userId']}. Error: {$errorInfo[2]}", null, 'database.log');
            return false;
        }
    } catch (PDOException $e) {
        containlog('ERROR', "PDO Exception in UpdateAllProfileByPGID for userId: {$newDataArray['userId']}. Message: {$e->getMessage()}", null, 'database.log');
        return false;
    }
}


?>