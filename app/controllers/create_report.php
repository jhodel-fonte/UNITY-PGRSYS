<?php
// process_report.php


include_once __DIR__ . '../../database/reports.php';
include_once __DIR__ . '../../database/images.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'message' => 'Report submission failed.'
];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    $user_id        = $_POST['user_id'] ?? null;
    $name           = $_POST['name'] ?? 'Anonymous Reporter'; 
    $description    = $_POST['description'] ?? null;
    $location_name  = $_POST['location'] ?? null;
    $address        = $_POST['address'] ?? null;
    $latitude       = $_POST['latitude'] ?? null;
    $longitude      = $_POST['longitude'] ?? null;
    $classification = $_POST['classification'] ?? null;
    $severity       = $_POST['severity'] ?? null;
    
    
    if (empty($description) || empty($latitude) || empty($longitude) || empty($address) || empty($classification)) {
        $response['message'] = 'Missing required fields (description, location, address, and classification).';
        echo json_encode($response);
        exit;
    }

    $dataArray = [
        'user_id'       => $user_id,
        'name'          => $name,
        'description'   => $description,
        'location'      => $location_name,
        'address'       => $address,
        'latitude'      => $latitude,
        'longitude'     => $longitude,
        'classification'=> $classification,
        'severity'      => $severity
    ];

    try {
        
        $report_id = createReport($dataArray); 
        
        if ($report_id && $report_id !== false) {
            
            if (!empty($_FILES['report_images']['name'][0])) {
                
                $full_upload_dir = __DIR__ . '../../../uploads/reports/'; 

                // FIX 2: Ensure the directory exists and is writable (check once)
                if (!is_dir($full_upload_dir)) {
                    if (!mkdir($full_upload_dir, 0777, true)) {
                        throw new Exception("Failed to create upload directory.");
                    }
                }
                
                $max_file_size = 10485760; // 10MB in bytes
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                foreach ($_FILES['report_images']['tmp_name'] as $key => $tmp_name) {
                    
                    $file_name = basename($_FILES['report_images']['name'][$key]);
                    $file_size = $_FILES['report_images']['size'][$key];
                    $file_error = $_FILES['report_images']['error'][$key];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    // Validation checks: file size, error, and extension
                    if ($file_error !== UPLOAD_ERR_OK) {
                        error_log("Upload error for {$file_name}: Code {$file_error}");
                        continue; 
                    }

                    if ($file_size > $max_file_size) {
                        error_log("Oversized file ignored: " . $file_name);
                        continue; 
                    }
                    
                    if (!in_array($file_ext, $allowed_extensions)) {
                        error_log("Invalid file type ignored: " . $file_name);
                        continue;
                    }
                    
                    $unique_file_name = $report_id . '_' . time() . '_' . uniqid() . '.' . $file_ext;
                    
                    
                    $file_path = $full_upload_dir . $unique_file_name;
                    
                    if (move_uploaded_file($tmp_name, $file_path)) {
                        insert_report_image_path($report_id, $unique_file_name);
                    } else {
                        error_log("File move failed for: " . $file_name);
                    }
                }
                
            }
            
            $response['success'] = true;
            $response['message'] = 'Your report has been successfully submitted and is under review.';
            
        } else {
            $response['message'] = 'Database insertion failed during report creation.';
        }

    } catch (Exception $e) {
        $response['message'] = 'Server error: ' . $e->getMessage();
        error_log('Report Submission Error: ' . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
exit;