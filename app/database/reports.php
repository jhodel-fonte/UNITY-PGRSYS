<?php

require_once __DIR__ .'../Db.php';
require_once __DIR__ .'../../utils/log.php';

function getAllReports() {
    $db = new Database();
    $conn = $db->getConn();
    $query = "SELECT * FROM reports JOIN profile ON reports.user_id = profile.userId";
    try {

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $reports = $stmt->fetchAll();
        return $reports;

    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }

}

function getAllReportImages() {
    $db = new Database();
    $conn = $db->getConn();
    $query = "SELECT * FROM report_images WHERE report_id IN (SELECT id FROM reports) order by report_id";
    $images = [];
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $images = $stmt->fetchAll();
        return $images;
    } catch (PDOException $e) {
        error_log("Query failed: " . $e->getMessage());
        return false;
    }
}

function getReportByUserId(int $id) {
    $db = new Database();
    $conn = $db->getConn();
    
    $query = "SELECT * FROM `reports` WHERE `user_id` = ?";
    
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);        
        $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $reports; 
        
    } catch (PDOException $e) {
        error_log("Database error in getReportByUserId({$id}): " . $e->getMessage());
        return false;
    }
}

function createReport($dataArray) {
    $db = new Database();
    $conn = $db->getConn();

    $values = [
        $dataArray['user_id'],
        $dataArray['name'],
        $dataArray['description'],
        $dataArray['location'],
        $dataArray['address'],
        $dataArray['latitude'],
        $dataArray['longitude'],
        $dataArray['classification'],
        $dataArray['severity']
    ];

    $query = "INSERT INTO reports(`user_id`, `name`, `description`, `location`, `address`, `latitude`, `longitude`, `classification`, severity) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    try {
        $stmt = $conn->prepare($query);
    
        $success = $stmt->execute($values);
        
        if ($success) {
            return $conn->lastInsertId();
        } else {
            return false;
        }

    } catch (PDOException $e) {
        containlog('Insert Error', "Database Error in createReport: " . $e->getMessage(), null, 'report.log');
        return false;
    }
}

function updateReportMLData(array $report, int $id) {

    $summary = $report['summary'] ?? 'Unknown Summary';
    $mlCategory = $report['ml_category'] ?? 'Unknown Category';
    $legitStatus = $report['legit_status'] ?? 'Unknown';

    $combinedMlCategory = $summary . ' - ' . $mlCategory;

    $db = new Database();
    $conn = $db->getConn();
    
    $query = "UPDATE `reports` SET `ml_category`= ?, `legit_status` = ? WHERE `id` = ?";
    
    $values = [
        $combinedMlCategory, 
        $legitStatus,        
        $id                 
    ];

    try {
        $stmt = $conn->prepare($query);
        $success = $stmt->execute($values);
        return $success && $stmt->rowCount() > 0;

    } catch (PDOException $e) {
        error_log("Database Error in updateReportMLData for report ID {$id}: " . $e->getMessage());
        throw $e;
    }
}



// //test
//     $dataArray = [
//         'user_id'       => $user_id,
//         'name'          => $name,
//         'report_type'   => $report_type,
//         'description'   => $description,
//         'location'      => $location_name,
//         'address'       => $address,
//         'latitude'      => $latitude,
//         'longitude'     => $longitude,
//         'classification'=> $classification,
//     ];

// createReport($dataArray)
