<?php
session_start();

require_once __DIR__ .'../../database/comms.php';

if (isset($_GET['image']) && $_GET['image'] == 'id') {
    // Set headers for JSON response
    header('Content-Type: application/json');

    // Define the directory where images will be saved
    $uploadDir = '../../uploads/';

    // Ensure the directory exists and is writable
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // // Check if the request method is POST
    // if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //     http_response_code(405);
    //     echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    //     exit;
    // }

    // Get the raw JSON POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Check if image data is present
    if (!isset($data['imageData']) || empty($data['imageData'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No image data received.']);
        exit;
    }

    $base64Image = $data['imageData'];

    // 1. Remove the data URI scheme prefix (e.g., "data:image/png;base64,")
    if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
        $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
        $fileExtension = strtolower($type[1]); // e.g., 'png' or 'jpeg'
    } else {
        // Assume default extension if prefix is missing
        $fileExtension = 'png';
    }

    // 2. Decode the Base64 string
    $imageData = base64_decode($base64Image);

    // Check if decoding failed
    if ($imageData === false) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Base64 decoding failed.']);
        exit;
    }

    // 3. Generate a unique file name
    $fileName = uniqid('id_', true) . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;

    // 4. Save the image data to the file
    if (file_put_contents($filePath, $imageData)) {
        // Success response
        $pgid = $_SESSION['userLoginData']['data']['pgCode'];
        if (!addImageToPgID($pgid, $fileName, 'ID')) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to Add In Database!', 'p' => $pgid]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            'success' => true, 
            'message' => 'ID image successfully saved.',
            'filename' => $fileName,
            'path' => $filePath
        ]);

    } else {
        // Error response
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to write image file to disk.']);
    }
    exit;
}

if (isset($_GET['image']) && $_GET['image'] == 'selfie') { 

    header('Content-Type: application/json');
    $upload_dir = '../../uploads/'; 

    // Check if the directory exists, if not, try to create it
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0775, true)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
            exit;
        }
    }

    // 1. Get the JSON data from the POST request
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    if (empty($data) || !isset($data['id'], $data['image_data'], $data['image_type'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid data format or missing ID/Image Data/Type.']);
        exit;
    }

    $user_id = (int)$data['id'];
    $image_data_url = $data['image_data']; // This is the Base64 dataURL (e.g., "data:image/jpeg;base64,...")
    $image_type = $data['image_type']; // e.g., 'selfie', 'id'

    // --- 2. Process and Validate the Image Data ---

    // Check if the data is a Base64 string with a header
    if (preg_match('/^data:image\/(.*?);base64,/', $image_data_url, $matches)) {
        $mime_type = $matches[1]; // Get the MIME type (e.g., 'jpeg', 'png')
        $base64_string = substr($image_data_url, strpos($image_data_url, ',') + 1); // Extract the pure Base64 part
    } else {
        http_response_code(400); 
        echo json_encode(['success' => false, 'message' => 'Invalid image data format.']);
        exit;
    }

    // Decode the Base64 string
    $image_binary_data = base64_decode($base64_string);

    if ($image_binary_data === false) {
        http_response_code(500); 
        echo json_encode(['success' => false, 'message' => 'Base64 decode failed.']);
        exit;
    }

    // Sanitize image type for filename safety
    $sanitized_type = preg_replace('/[^a-zA-Z0-9_-]/', '', $image_type);

    $timestamp = time(); 

    // Example filename: 12345_selfie_1678886400.jpeg
    $file_extension = ($mime_type === 'png') ? 'png' : 'jpeg';
    $filename = "{$user_id}_{$sanitized_type}_{$timestamp}.{$file_extension}";
    $file_path = $upload_dir . $filename;

    // --- 4. Save the File ---

    if (file_put_contents($file_path, $image_binary_data) !== false) {
        $pgid = $data['id'];
        if (!updateProfileImage($pgid, $filename)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to Add In Database!', 'p' => $pgid]);
            exit;
        }
        // Success response
        echo json_encode([
            'success' => true, 
            'message' => 'Image uploaded successfully.', 
            'filename' => $filename
        ]);
    } else {
        // Failure response
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'message' => 'Failed to save image file. Check directory permissions.'
        ]);
    }

}

?>