<?php
include_once __DIR__ .'../../database/team_table.php'; 

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Invalid request or missing data.'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {
    if ( isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete' ){
        
        $team_id = $_POST['id'];
        
        $result = deleteTeam($team_id); 

        if ($result == true) {
            $response = [
                'success' => true,
                'message' => 'Team ID ' . htmlspecialchars($team_id) . ' has been permanently deleted.'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Deletion failed. The team may not exist, or a database error occurred.'
            ];
        }
    } else {
        $response['message'] = 'Missing ID or incorrect action specified.';
    }
}

echo json_encode($response);
exit;