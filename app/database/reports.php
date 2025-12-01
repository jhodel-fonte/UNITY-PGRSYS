<?php

require_once __DIR__ .'../Db.php';

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

?>