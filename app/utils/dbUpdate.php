<?php

// include_once __DIR__ ."../../../data/Db.php";
require_once __DIR__ . '/common.php';

function getTableRows($dbConn, $table) {
    // Validate table name to prevent SQL injection
    $allowedTables = ['account', 'profile', 'reports', 'role', 'status'];
    $table = sanitizeInput($table);
    
    if (!in_array($table, $allowedTables, true)) {
        throw new Exception("Invalid table name: " . $table);
    }
    
    $query = $dbConn->prepare("SHOW COLUMNS FROM `" . $table . "`");
    $query->execute();
    $result = $query->fetchAll();

    $temp = [];
    if ($result) {
        foreach ($result as $row) {
            $temp[] = $row['Field'];
        }
    }

    return $temp;
}



// $db = new Database();

// var_dump(getTableRows($db->getConn(), "account"));


?>
