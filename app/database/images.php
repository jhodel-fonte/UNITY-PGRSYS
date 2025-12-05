<?php

include_once __DIR__ .'../../database/Db.php';

function insert_report_image_path($report_id, $path) {
    $database = new Database();
    
    $sql = "INSERT INTO report_images(`report_id`, `photo`) VALUES (?, ?)";

    try {
        $conn = $database->getConn();
    
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$report_id, $path]);
        return $success;
        
    } catch (PDOException $e) {
        error_log("Database Error in insert_report_image_path: " . $e->getMessage());
        return false; 
    }
}