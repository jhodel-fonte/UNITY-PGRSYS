<?php

include_once 'Db.php';
require_once __DIR__ . '/../utils/common.php';

function addTeam($arrayData) {
    $name = sanitizeInput($arrayData["name"]) ?? null;
    $number = sanitizeInput($arrayData["contact_number"]) ?? null;
    $email = sanitizeInput($arrayData["email"]) ?? null;
    $address = sanitizeInput($arrayData["address"]) ?? null;
    $classification = sanitizeInput($arrayData["classification"]) ?? null;


    $database = new Database();
    $conn = $database->getConn();

    $sql = 'INSERT INTO `response_team`(`name`, `contact_number`, `email`, `address`, `classification`) 
            VALUES (?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    
    $success = $stmt->execute([$name, $number, $email, $address, $classification]); 
    
    if ($success) {
        $newTeamId = $conn->lastInsertId();
        
        return [
            'success' => true,
            'newTeamId' => $newTeamId
        ];
    } else {
        $errorInfo = $stmt->errorInfo();
        containlog('ERROR', "Database Error executing INSERT in addTeams. Error: {$errorInfo[2]}", null, 'database.log'); 
        
        return [
            'success' => false,
            'newTeamId' => null,
            'message' => $errorInfo[2]
        ];
    }
}

function getNotAssignedResponseUser() {
    $database = new Database();
    $conn = $database->getConn();

    $sql = "SELECT
    acc.username,
    acc.mobileNum,
    rl.name AS role,
    st.Name AS status,
    acc.pgCode,
    acc.email,
    acc.is_approved,
    profile.*
    FROM
    `account` AS `acc`
    LEFT JOIN
    roles AS rl ON rl.roleId = acc.roleId
    LEFT JOIN
    status AS st ON st.statusId = acc.statusId
    LEFT JOIN
    profile ON profile.userId = acc.pgCode
    LEFT JOIN
    members_team AS mt ON mt.member_id = acc.pgCode
    WHERE
    acc.roleId = 2
    AND mt.team_id IS NULL;";
    
    $stmt = $conn->prepare($sql);
    $success = $stmt->execute(); 
    
    if ($success) {
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    } else {
        $errorInfo = $stmt->errorInfo();
        containlog('ERROR', "Database Error executing query in getReposnseUsers. Error: {$errorInfo[2]}", null, 'database.log'); 
        return [];
    }
}

function setTeamStatus($status, $id) {
    $sanitized_status = filter_var($status, FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 0, 'max_range' => 1)
    ));
    
    if ($sanitized_status === false) {
        error_log("Invalid status value provided: " . $status);
        return false; 
    }

    try {
        $database = new Database();
        $conn = $database->getConn();

        $sql = "UPDATE `response_team` SET `is_active` = ? WHERE `team_id` = ?";
        
        $stmt = $conn->prepare($sql);
    
        $success = $stmt->execute([$sanitized_status, $id]);        
        return $success; 
        
    } catch (PDOException $e) {
        error_log("Database Error in setTeamStatusActive: " . $e->getMessage());
        return false;
    }
}

function updateTeam(array $data): bool {
    $teamId = $data['team_id']; 
    
    $sql = "UPDATE `response_team` 
            SET 
                `name`=?, 
                `contact_number`= ?,
                `is_active`= ?,
                `email`= ?,
                `address`= ?, 
                `classification`= ?
    WHERE team_id = ?";
    
    $values = [
        sanitizeInput($data['name'] ?? ''),
        sanitizeInput($data['contact_number'] ?? ''),
        $data['is_active'] ?? 0,
        sanitizeInput($data['email'] ?? ''),
        sanitizeInput($data['address'] ?? ''),
        sanitizeInput($data['classification'] ?? ''),
        $teamId
    ];

    try {
        $database = new Database();
        $conn = $database->getConn();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($values);
        return $success && $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        error_log("Database Error in updateTeam: " . $e->getMessage());
        throw $e;
    }
}

function addMembertoTeam($teamid, $userId) {
    $sql = "INSERT INTO `members_team`(`team_id`, `member_id`) VALUES (?, ?)";
    
    $values = [$teamid, $userId]; 

    try {
    
        $database = new Database();
        $conn = $database->getConn();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($values);
        return $success && $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        
        error_log("Database Error in addMembertoTeam: " . $e->getMessage());
        throw $e;
    }
}

function deleteTeam($team_id) {
    $sql = "DELETE FROM response_team WHERE `team_id` = ?";
    $values = [$team_id]; 

    try {
        $database = new Database();
        $conn = $database->getConn();
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($values);
        
        return $success && $stmt->rowCount() > 0;
        
    } catch (PDOException $e) {
        containlog('ERROR', "Database Error executing query in Delete Teams. Error:", null, 'database.log'); 
        return $e;
    }
}
