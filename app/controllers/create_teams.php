<?php

header('Content-Type: application/json');

require_once __DIR__ . "/../database/team_table.php";

$response = [
    'success' => false,
    'message' => 'An unknown error occurred.',
    'data' => null
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $teamName = trim($_POST['name'] ?? '');
    $contact = trim($_POST['contact_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $classification = trim($_POST['classification'] ?? null);

    if (empty($teamName) || empty($contact)) {
        http_response_code(400); // Bad Request
        $response['message'] = "Team Name and Contact Number are required.";

    } else {
        $newTeamData = [
            "name" => $teamName,
            "contact_number" => $contact,
            "email" => $email,
            "address" => $address,
            "classification" => $classification,
            "is_active" => 1,
            "dateCreated" => date('Y-m-d H:i:s')
        ];

        try {
            $newTeamId = addTeam($newTeamData);

            if ($newTeamId) {
                http_response_code(201);
                $response['success'] = true;
                $response['message'] = "Response team created successfully!";
                $response['data'] = ['team_id' => $newTeamId];
            } else {
                http_response_code(500); // Internal Server Error
                $response['message'] = "Error: Failed to save the team record. No ID returned.";
            }

        } catch (Exception $e) {
            
            http_response_code(409);
            $errorMessage = $e->getMessage();
            
            if (strpos($errorMessage, 'Duplicate entry') !== false) {
                $response['message'] = "Creation failed: A team with that name or contact number may already exist.";
                
            } else {
                $response['message'] = "Database Error: " . $errorMessage;
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