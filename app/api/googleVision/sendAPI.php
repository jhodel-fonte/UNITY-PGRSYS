<?php


function googleVisionApi(string $imageBase64): array {
    $apiKey = "AIzaSyC-W5c8FVLpKl4biBvg9iHaWSy--rzDQVI";
    $link = "https://vision.googleapis.com/v1/images:annotate?key=" . $apiKey;

    $requestBody = [
        'requests' => [
            [
                'image' => [
                    'content' => $imageBase64
                ],
                'features' => [
                    ['type' => 'LABEL_DETECTION', 'maxResults' => 5],
                    ['type' => 'WEB_DETECTION', 'maxResults' => 5],
                    ['type' => 'SAFE_SEARCH_DETECTION'] 
                ]
            ]
        ]
    ];

    $ch = curl_init($link);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestBody));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen(json_encode($requestBody))
    ]);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        error_log("Google Vision API call failed with HTTP code: {$http_code}. Response: {$result}");
        return ['summary' => 'API Error', 'ml_category' => 'Error', 'legit_status' => 'Unknown'];
    }

    $data = json_decode($result, true);
    $response = $data['responses'][0] ?? [];
    
    $mlData = [
        'summary' => null,
        'ml_category' => null,
        'legit_status' => 'Unknown' 
    ];

    // Extract ML Category (Top Label)
    if (isset($response['labelAnnotations'][0]['description'])) {
        $mlData['ml_category'] = $response['labelAnnotations'][0]['description'];
    }

    // Extract Summary (Best Web Entity Match)
    if (isset($response['webDetection']['bestGuessLabels'][0]['label'])) {
         $mlData['summary'] = $response['webDetection']['bestGuessLabels'][0]['label'];
    } elseif ($mlData['ml_category']) {
        $mlData['summary'] = $mlData['ml_category'];
    }

    // Extract Legit Status (Safe Search Check)
    if (isset($response['safeSearchAnnotation'])) {
        $safeAnnotations = $response['safeSearchAnnotation'];
        
        // Define ligitemate
        $suspicious_likelihoods = ['LIKELY', 'POSSIBLE', 'VERY_LIKELY'];
        $isSuspicious = false;
        foreach (['adult', 'violence', 'racy'] as $category) {
            if (isset($safeAnnotations[$category]) && in_array($safeAnnotations[$category], $suspicious_likelihoods)) {
                $isSuspicious = true;
                break;
            }
        }
        
        $mlData['legit_status'] = $isSuspicious ? 'Suspicious' : 'Legit';
    }

    return $mlData;
}


/* 
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'No image data received.'];

$response = ['success' => false, 'message' => 'Invalid request or no data received.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read the raw JSON data from the request body
    $json_data = file_get_contents('php://input');
    
    // 2. Decode the JSON data
    $data = json_decode($json_data, true);
    
    // 3. Check for successful decode and the required key
    if (isset($data['image_base64']) && !empty($data['image_base64'])) {
        
        $imageBase64 = $data['image_base64'];
        
        // Clean the string (remove data URI prefix if present)
        $imageBase64 = preg_replace('/^data:image\/(png|jpeg|gif);base64,/', '', $imageBase64);
        
        try {
            $analysisResult = googleVisionApi($imageBase64);
            
            $response = [
                'success' => true,
                'message' => 'Image analysis complete.',
                'data' => $analysisResult
            ];
            
        } catch (\Throwable $e) {
            error_log("Unhandled Exception in Vision API Handler: " . $e->getMessage());
            $response = [
                'success' => false,
                'message' => 'An internal error occurred during processing: ' . $e->getMessage()
            ];
        }
    } else {
        $response['message'] = 'JSON data is invalid or "image_base64" key is missing/empty.';
    }
}

// Output the final JSON response
echo json_encode($response);
exit; */

