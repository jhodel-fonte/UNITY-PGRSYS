<?php

header('Content-Type: application/json');
require_once __DIR__ . "/../database/team_table.php";
require_once __DIR__ . "/../utils/log.php";


$response = [
    'success' => false,
    'message' => 'An unknown error occurred.',
    'data' => null
];


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $teamId = (int)($_POST['team_id'] ?? 0);
    $teamName = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $isActive = (int)($_POST['is_active'] ?? 0);
    $classification = trim($_POST['classification'] ?? 0);

    if ($teamId <= 0 || empty($teamName) || empty($contact)) {
        http_response_code(400);
        $response['message'] = "Team ID, Name, and Contact Number are required for update.";

    } else {
        
        $updatedTeamData = [
            "team_id" => $teamId, 
            "name" => $teamName,
            "contact_number" => $contact,
            "email" => $email,
            "address" => $address,
            "is_active" => $isActive,
            "classification" => $classification
        ];

        try {
            // updateTeam returns TRUE if affected rows > 0, and FALSE otherwise (no changes).
            $success = updateTeam($updatedTeamData); 

            if ($success) {
                // Case 1: Successful update (at least one row changed) 
                http_response_code(200); // OK
                $response['success'] = true;
                $response['message'] = "Response team updated successfully!";
                $response['data'] = [
                    'newName' => $updatedTeamData['name'],
                    'newStatus' => $updatedTeamData['is_active']
                ]; 
            } else {
                // Case 2: Query ran successfully, but 0 rows were affected (no change in data)
                http_response_code(200); // OK, but informational message
                $response['success'] = true; 
                $response['message'] = "No changes were detected or saved for this team.";
            }

        } catch (Exception $e) {
            // Case 3: Database Error (e.g., PDOException for Duplicate entry)
            http_response_code(409); // Conflict

            $errorMessage = $e->getMessage();
            
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                $response['message'] = "Update failed: A team with that Name or Contact Number may already exist.";
                
            } else {
                $response['message'] = "Database Error: Failed to update team. Please check the server logs.";
                error_log(" " . $errorMessage);
                containlog("Error", "Update Team Endpoint Error:" . $errorMessage, null, "database.log");
            }
        }
    }
} else {
    // Handle non-POST requests
    http_response_code(405); // Method Not Allowed
    $response['message'] = "Method not allowed.";
}

// Output the final JSON response
echo json_encode($response);
exit;

?>