<?php
// process_report.php

include_once __DIR__ . '../../database/reports.php';
include_once __DIR__ . '../../database/images.php';
include_once __DIR__ . '../../api/googleVision/sendAPI.php';
// include_once __DIR__ . '../../utils/imageHandler.php';

function imagetoBase64(string $filePath): string|false {
    if (!file_exists($filePath)) {
        return false;
    }
    return base64_encode(file_get_contents($filePath));
}

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
        
        // Step 1: Insert basic report data.
        $report_id = createReport($dataArray); 
        
        if ($report_id && $report_id !== false) {
        
            if (!empty($_FILES['report_images']['name'][0])) {
                
                
                $relative_upload_path_db = '../../uploads/reports/'; 
                $full_upload_dir_fs = __DIR__ . '../../../uploads/reports/'; 

                if (!is_dir($full_upload_dir_fs)) {
                    if (!mkdir($full_upload_dir_fs, 0777, true)) {
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

                    // Validation checks
                    if ($file_error !== UPLOAD_ERR_OK || $file_size > $max_file_size || !in_array($file_ext, $allowed_extensions)) {
                        error_log("Skipping invalid or oversized file: " . $file_name);
                        continue; 
                    }
                    
                    $unique_file_name = $report_id . '_' . time() . '_' . uniqid() . '.' . $file_ext;
                    
                    $file_path_fs = $full_upload_dir_fs . $unique_file_name;
                    
                    if (move_uploaded_file($tmp_name, $file_path_fs)) {
                        //  to database
                        insert_report_image_path($report_id, $unique_file_name); 

                        $imagePath = "C:/xampp/htdocs/UNTY-PGRSYS/uploads/reports/" . $unique_file_name;
                    } else {
                        error_log("File move failed for: " . $file_name);
                    }
                }
                
            }
            
            //postimage
            // $image = $_FILES['report_images']['tmp_name'];
            // var_dump($image);
            $response['success'] = true;
            $response['message'] = 'Your report has been successfully submitted and is under review.';
            
            try {  
                $imageBase64 = imagetoBase64($imagePath);
                $mlResults = googleVisionApi($imageBase64);
                updateReportMLData($mlResults, $report_id);
                
            } catch (Throwable $e) {
                error_log("ML processing failed for report ID {$report_id}: " . $e->getMessage());
            }

            
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