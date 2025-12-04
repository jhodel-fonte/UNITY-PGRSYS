<?php
// Set header to return JSON
header('Content-Type: application/json');

$teamId = $_GET['team_id'] ?? null;

if (empty($teamId) || !is_numeric($teamId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid Team ID provided.']);
    exit;
}
$sql = "SELECT userId, firstName, lastName, email, contact_number, role, status 
        FROM users 
        WHERE team_id IS NULL OR team_id != :team_id
        AND status = 'Active'"; // Example filtering

try {

    // --- Temporary Mock Data (Replace with real data from DB) ---
    $users = [
        ['userId' => 101, 'firstName' => 'Sarah', 'lastName' => 'Conner', 'email' => 'sarah@test.com', 'contact_number' => null, 'role' => 'Technician', 'status' => 'Active'],
        ['userId' => 102, 'firstName' => 'Kyle', 'lastName' => 'Reese', 'email' => null, 'contact_number' => '0917-555-1234', 'role' => 'Driver', 'status' => 'Active'],
        // ... more users ...
    ];
    // --- End Mock Data ---


    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>