<?php
ob_start();
include_once __DIR__ .'../../database/team_table.php';


header('Content-Type: application/json');


$response = [
    'success' => false,
    'message' => 'Invalid request or missing data.'
];

if ($_SERVER['REQUEST_METHOD'] == 'POST' ) {

    if ( isset($_POST['team_id']) && isset($_POST['user_id']) ){
        $user_id = $_POST['user_id'];
        $team_id = $_POST['team_id']; 


        $result = addMembertoTeam($team_id, $user_id);
        
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'User (ID: ' . htmlspecialchars($user_id) . ') successfully added to Team ID: ' . htmlspecialchars($team_id)
            ];
        } else {

            $response = [
                'success' => false,
                'message' => 'Failed to add member. The user may already be on this team, or a database error occurred.'
            ];
        }
    }
}

ob_clean();
echo json_encode($response);
exit;